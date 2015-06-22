<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages_model extends CI_Model
{
    /**
     * Send a New Message
     *
     * @param   integer  $sender_id
     * @param   mixed    $recipients  A single integer or an array of integers
     * @param   string   $subject
     * @param   string   $body
     * @param   integer  $priority
     * @return  boolean
     */
	function send_new_message($sender_id, $recipients, $subject, $body) {

		// Insert thread
		$data = array('status' => "1");
		$this->db->insert('message_threads', $data);
		$thread_id = $this->db->insert_id();

		if ( ! is_array($recipients)) {
			// Insert message
			$data = array(
				'sender_id' => $sender_id,
				'recipient_id' => $recipients,
				'subject' => $subject,
				'body' => $body,
				'thread_id' => $thread_id,
				'status' => "1"
			);
			$this->db->insert('messages', $data);
			$message_id = $this->db->insert_id();
			
		}
        else {
			foreach ($recipients as $recipient) {
				// Insert message
				$data = array(
					'sender_id' => $sender_id,
					'recipient_id' => $recipient,
					'subject' => $subject,
					'body' => $body,
					'thread_id' => $thread_id,
					'status' => "1"
				);
				$this->db->insert('messages', $data);
				$message_id = $this->db->insert_id();
			}
        }

        return $thread_id;
    }

	function get_admin_user_ids() {
		$query = $this->db->get_where('users_groups', array('group_id' => '1'))->result_array();
		$user_ids = array();
		foreach ( $query as $q ) {
			$user_ids[] = $q['user_id'];
		}
		return $user_ids;
	}
	
	function get_curator_user_ids() {
		$query = $this->db->get_where('users_groups', array('group_id' => '3'))->result_array();
		$user_ids = array();
		foreach ( $query as $q ) {
			$user_ids[] = $q['user_id'];
		}
		return $user_ids;
	}
	
    // ------------------------------------------------------------------------

    /**
     * Reply to Message
     *
     * @param   integer  $reply_msg_id
     * @param   integer  $sender_id
     * @param   string   $body
     * @param   integer  $priority
     * @return  boolean
     */
    function reply_to_message($reply_msg_id, $sender_id, $subject, $body, $recipients) {

		// Get the thread id to keep messages together
		if ( ! $thread_id = $this->get_thread_id_from_message($reply_msg_id)) {
			return FALSE;
		}

		if ( ! is_array($recipients)) {
	     // Add this message
			$data = array(
				'sender_id' => $sender_id,
				'subject' => $subject,
				'body' => $body,
				'thread_id' => $thread_id,
				'recipient_id' => $recipients,
				'status' => "1"
			);
			$this->db->insert('messages', $data);
			$message_id = $this->db->insert_id();
		}
		else {
			foreach ($recipients as $recipient) {
				$data = array(
					'sender_id' => $sender_id,
					'subject' => $subject,
					'body' => $body,
					'thread_id' => $thread_id,
					'recipient_id' => $recipient,
					'status' => "1"
				);
				$this->db->insert('messages', $data);
				$message_id = $this->db->insert_id();				
			}
		}
        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Get a Single Message
     *
     * @param  integer $msg_id
     * @param  integer $user_id
     * @param  string $type
     * @return array
     */
    function get_message($message_id, $user_id, $type = NULL) { // $type is whether the message has been clicked on from inbox or sent tabs - this influences the mysql query for whether the user_id is the sender_id or the recipient_id in the query
//		$query = $this->db->get_where('messages', array('message_id' => $message_id, 'recipient_id' => $user_id));
		
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.sender_id');
		if ( $type == "inbox" ) {
			$this->db->where(array('recipient_id' => $user_id, 'message_id' => $message_id ));
		}
		elseif ( $type == "sent" ) {
			$this->db->where(array('sender_id' => $user_id, 'message_id' => $message_id ));
		}
		else {
			$this->db->where(array('recipient_id' => $user_id, 'message_id' => $message_id ));
		}
		$query = $this->db->get();
		
		
//		print $this->db->last_query() . "<br />";
//		return $query->row();
//		print_r($query->row());
		$row = (array) $query->row();
		return $row;
//        return $query->result_array();
    }
	
    // ------------------------------------------------------------------------

    /**
     * Get a Full Thread
     *
     * @param   integer  $thread_id
     * @param   integer  $user_id
     * @param   boolean  $full_thread
     * @param   string   $order_by
     * @return  array
     */
    function get_full_thread($thread_id, $user_id, $full_thread = FALSE, $order_by = 'asc')
    {
        $sql = 'SELECT m.*, s.status, t.subject, '.USER_TABLE_USERNAME .
        ' FROM ' . $this->db->dbprefix . 'msg_participants p ' .
        ' JOIN ' . $this->db->dbprefix . 'msg_threads t ON (t.id = p.thread_id) ' .
        ' JOIN ' . $this->db->dbprefix . 'msg_messages m ON (m.thread_id = t.id) ' .
        ' JOIN ' . $this->db->dbprefix . USER_TABLE_TABLENAME . ' ON (' . USER_TABLE_ID . ' = m.sender_id) '.
        ' JOIN ' . $this->db->dbprefix . 'msg_status s ON (s.message_id = m.id AND s.user_id = ? ) ' .
        ' WHERE p.user_id = ? ' .
        ' AND p.thread_id = ? ';

        if ( ! $full_thread)
        {
            $sql .= ' AND m.cdate >= p.cdate';
        }

        $sql .= ' ORDER BY m.cdate ' . $order_by;

        $query = $this->db->query($sql, array($user_id, $user_id, $thread_id));

        return $query->result_array();
    }

    // ------------------------------------------------------------------------

    /**
     * Get All Threads
     *
     * @param   integer  $user_id
     * @param   boolean  $full_thread
     * @param   string   $order_by
     * @return  array
     */
    function get_all_threads($user_id, $full_thread = FALSE, $order_by = 'asc')
    {
        $sql = 'SELECT m.*, s.status, t.subject, '.USER_TABLE_USERNAME .
        ' FROM ' . $this->db->dbprefix . 'msg_participants p ' .
        ' JOIN ' . $this->db->dbprefix . 'msg_threads t ON (t.id = p.thread_id) ' .
        ' JOIN ' . $this->db->dbprefix . 'msg_messages m ON (m.thread_id = t.id) ' .
        ' JOIN ' . $this->db->dbprefix . USER_TABLE_TABLENAME . ' ON (' . USER_TABLE_ID . ' = m.sender_id) '.
        ' JOIN ' . $this->db->dbprefix . 'msg_status s ON (s.message_id = m.id AND s.user_id = ? ) ' .
        ' WHERE p.user_id = ? ' ;

        if (!$full_thread)
        {
            $sql .= ' AND m.cdate >= p.cdate';
        }

        $sql .= ' ORDER BY t.id ' . $order_by. ', m.cdate '. $order_by;

        $query = $this->db->query($sql, array($user_id, $user_id));

        return $query->result_array();
    }

	
    // ------------------------------------------------------------------------

    /**
     * Get All Messages For a User
     *
     * @param   integer  $user_id
     * @return  array
     */
    function get_messages_for_user($user_id)
    {
		$this->db->select('*');
		$this->db->from('messages');
//		$this->db->join('messages', 'messages.thread_id = message_participants.thread_id');
//		$this->db->join('message_threads', 'message_threads.thread_id = message_participants.thread_id');
		$this->db->join('users', 'users.id = messages.sender_id');
		$this->db->where(array('recipient_id' => $user_id ));
//		$this->db->where('sender_id !=', $user_id);
		$query = $this->db->get();
//		print "LAST: " . $this->db->last_query() . "<br />";
//		print_r($query);
//		print_r($query->result_array());
        return $query->result_array();
    }
	
    // ------------------------------------------------------------------------

    /**
     * Update message status (0 = read, 1 = unread)
     *
     * @param   integer  $status
     * @param   integer  $message_id
     * @param   integer  $user_id
     */
    function update_message_status($status, $message_id, $user_id) {
		$data = array( 'status' => $status );
		$this->db->where('message_id', $message_id);
		$this->db->where('recipient_id', $user_id);
		$this->db->update('messages', $data);
		return $this->db->affected_rows();
    }

    // ------------------------------------------------------------------------

    /**
     * Get All Messages For a User
     *
     * @param   integer  $user_id
     * @return  array
     */
    function get_sent_messages_for_user($user_id)
    {
		$this->db->select('*');
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.recipient_id');
		$this->db->where(array('sender_id' => $user_id ));
		$query = $this->db->get();
        return $query->result_array();
    }
	
    // ------------------------------------------------------------------------

    /**
     * Change Message Status
     *
     * @param   integer  $msg_id
     * @param   integer  $user_id
     * @param   integer  $status_id
     * @return  integer
     */
//    function update_message_status($msg_id, $user_id, $status_id)
//    {
//        $this->db->where(array('message_id' => $msg_id, 'user_id' => $user_id ));
//        $this->db->update('msg_status', array('status' => $status_id ));
//        return $this->db->affected_rows();
//    }

    // ------------------------------------------------------------------------

    /**
     * Add a Participant
     *
     * @param   integer  $thread_id
     * @param   integer  $user_id
     * @return  boolean
     */
    function add_participant($thread_id, $user_id)
    {
        $this->db->trans_start();

        $participants[] = array('thread_id' => $thread_id,'user_id' => $user_id);

        $this->_insert_participants($participants);

        // Get Messages by Thread
        $messages = $this->_get_messages_by_thread_id($thread_id);

        foreach ($messages as $message)
        {
            $statuses[] = array('message_id' => $message['id'], 'user_id' => $user_id, 'status' => MSG_STATUS_UNREAD);
        }

        $this->_insert_statuses($statuses);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Remove a Participant
     *
     * @param   integer  $thread_id
     * @param   integer  $user_id
     * @return  boolean
     */
    function remove_participant($thread_id, $user_id)
    {
        $this->db->trans_start();

        $this->_delete_participant($thread_id, $user_id);
        $this->_delete_statuses($thread_id, $user_id);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Valid New Participant - because of CodeIgniter's DB Class return style,
     *                         it is safer to check for uniqueness first
     *
     * @param   integer $thread_id
     * @param   integer $user_id
     * @return  boolean
     */
    function valid_new_participant($thread_id, $user_id)
    {
        $sql = 'SELECT COUNT(*) AS count ' .
        ' FROM ' . $this->db->dbprefix . 'msg_participants p ' .
        ' WHERE p.thread_id = ? ' .
        ' AND p.user_id = ? ';

        $query = $this->db->query($sql, array($thread_id, $user_id));

        if ($query->row()->count)
        {
            return FALSE;
        }

        return TRUE;
    }

    // ------------------------------------------------------------------------

    /**
     * Application User
     *
     * @param   integer  $user_id`
     * @return  boolean
     */
    function application_user($user_id)
    {
        $sql = 'SELECT COUNT(*) AS count ' .
        ' FROM ' . $this->db->dbprefix . USER_TABLE_TABLENAME .
        ' WHERE ' . USER_TABLE_ID . ' = ?' ;

        $query = $this->db->query($sql, array($user_id));

        if ($query->row()->count)
        {
            return TRUE;
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Get Participant List
     *
     * @param   integer  $thread_id
     * @param   integer  $sender_id
     * @return  mixed
     */
    function get_participant_list($thread_id, $sender_id = 0)
    {
        if ($results = $this->get_thread_participants($thread_id))
        {
            return $results;
        }
        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Get Message Count
     *
     * @param   integer  $user_id
     * @param   integer  $status_id
     * @return  integer
     */
    function get_message_count($user_id, $status_id = "1")
    {
        $query = $this->db->select('COUNT(*) AS msg_count')->where(array('recipient_id' => $user_id, 'status' => $status_id ))->get('messages');

        return $query->row()->msg_count;
    }
	
    // ------------------------------------------------------------------------

    /**
     * Delete Message
     *
     * @param   integer  $message_id
     * @param   integer  $user_id
     * @return  integer
     */
	
    function delete_message($message_id, $user_id) {
		$this->db->delete('messages', array('message_id' => $message_id, 'recipient_id' => $user_id));
		if ( $this->db->affected_rows() ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
    }
	
    // ------------------------------------------------------------------------
    // Private Functions from here out!
    // ------------------------------------------------------------------------

    /**
     * Insert Thread
     *
     * @param   string  $subject
     * @return  integer
     */
    private function _insert_thread($subject)
    {
        $insert_id = $this->db->insert('msg_threads', array('subject' => $subject));

        return $this->db->insert_id();
    }

    /**
     * Insert Message
     *
     * @param   integer  $thread_id
     * @param   integer  $sender_id
     * @param   string   $body
     * @param   integer  $priority
     * @return  integer
     */
    private function _insert_message($thread_id, $sender_id, $body, $priority)
    {
        $insert['thread_id'] = $thread_id;
        $insert['sender_id'] = $sender_id;
        $insert['body']      = $body;
        $insert['priority']  = $priority;

        $insert_id = $this->db->insert('msg_messages', $insert);

        return $this->db->insert_id();
    }

    /**
     * Insert Participants
     *
     * @param   array  $participants
     * @return  bool
     */
    private function _insert_participants($participants)
    {
        return $this->db->insert_batch('msg_participants', $participants);
    }

    /**
     * Insert Statuses
     *
     * @param   array  $statuses
     * @return  bool
     */
    private function _insert_statuses($statuses)
    {
        return $this->db->insert_batch('msg_status', $statuses);
    }

    /**
     * Get Thread ID from Message
     *
     * @param   integer  $msg_id
     * @return  integer
     */
    public function get_thread_id_from_message($msg_id)
    {
        $query = $this->db->select('thread_id')->get_where('messages', array('message_id' => $msg_id));

        if ($query->num_rows())
        {
            return $query->row()->thread_id;
        }
        return 0;
    }

    /**
     * Get Messages by Thread
     *
     * @param   integer  $thread_id
     * @return  array
     */
    private function _get_messages_by_thread_id($thread_id)
    {
        $query = $this->db->get_where('msg_messages', array('thread_id' => $thread_id));

        return $query->result_array();
    }


    /**
     * Get Thread Particpiants
     *
     * @param   integer  $thread_id
     * @param   integer  $sender_id
     * @return  array
     */
    public function get_thread_participants($thread_id) {
		$this->db->select('sender_id, recipient_id, username, id');
		$this->db->from('messages');
		$this->db->join('users', 'users.id = messages.recipient_id');
//		$this->db->join('users', 'users.id = messages.sender_id');
		$this->db->where(array('thread_id' => $thread_id ));
		$query = $this->db->get();
		return $query->result_array();
    }	

    /**
     * Delete Participant
     *
     * @param   integer  $thread_id
     * @param   integer  $user_id
     * @return  boolean
     */
    private function _delete_participant($thread_id, $user_id)
    {
        $this->db->delete('msg_participants', array('thread_id' => $thread_id, 'user_id' => $user_id));

        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Delete Statuses
     *
     * @param   integer  $thread_id
     * @param   integer  $user_id
     * @return  boolean
     */
    private function _delete_statuses($thread_id, $user_id)
    {
        $sql = 'DELETE s FROM msg_status s ' .
        ' JOIN ' . $this->db->dbprefix . 'msg_messages m ON (m.id = s.message_id) ' .
        ' WHERE m.thread_id = ? ' .
        ' AND s.user_id = ? ';

        $query = $this->db->query($sql, array($thread_id, $user_id));

        return TRUE;
    }
	
	function lookupUsers($keyword) {
		$query = $this->db->query("SELECT * FROM users WHERE username LIKE '$keyword%' ORDER BY username LIMIT 10");
		return $query;
	}
	
}

/* end of file mahana_model.php */