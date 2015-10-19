<div class="container">
	<div class="row-fluid">
		<div class="span6 pagination-centered">
			<div class="well-group">
				<!--<h3>Cafe Variome in-a-box</h3>-->
				<h4>A complete data discovery solution for diagnostic networks, disease consortia and research communities</h4>
				<p>We offer a complete data sharing software solution based upon enabling the 'open discovery' of data (rather than data 'sharing') for example, between networks of diagnostic laboratories or disease consortia that know/trust each other and share an interest in certain causative genes or diseases.</p>
				<p>We welcome any potential collaborations/partnerships with interested parties and would be particularly keen to hear from diagnostic networks and disease consortia. Please fill in the form to register your interest.</p>
				
				<?php echo img("resources/images/cafevariome/cafevariome-in-a-box.png"); ?>
				<br />
				<br />
				<br />
			</div>
		</div>
		<div class="span6 pagination-centered">
			<div class="well-group">
				<legend>Register Your Interest</legend>
				<p>Please provide the following details:</p>
				<p>
					<?php
						echo '<b>' . validation_errors() . '</b>';
						echo form_open('about/inabox');
					?>
					<label>Contact Name:</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span>
						<?php
						$fullname_data = array('name' => 'fullname', 'id' => 'fullname', 'value' => set_value('fullname'));
						echo form_input($fullname_data);
						?>
					</div>
				</p>
				<p>
					<label>Email:</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span>
						<?php 
						$email_data = array('name' => 'email', 'id' => 'email', 'value' => set_value('email') );
						echo form_input($email_data);
						?>
					</div>
				</p>
				<p>
					<label>Institute/Laboratory/Consortium/Network:</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-briefcase"></i></span>
						<?php 
						$institute_data = array('name' => 'institute', 'id' => 'institute', 'value' => set_value('institute'));
						echo form_input($institute_data);
						?>
					</div>
				</p>
				<p>
					<label>Additional Information/specific requirements:</label>
					<?php 
					$description_data = array('name' => 'description', 'id' => 'description', 'rows' => '4', 'value' => set_value('description') );
					echo form_textarea($description_data);
					?>
				</p>
<!--				<br />
				<p>
					<label>Agree to the <a href="#myModal" data-toggle="modal">conditions of use</a>:</label>
					<?php // echo form_checkbox('conditions', 'accept', FALSE);?>
				</p>
				<br />-->
				<button type="submit" class="btn"><i class="icon-ok"></i>  Submit</button>
				</form>
			</div>
		</div>
	</div>
<hr>

</div><!--/.container-->

<div id="myModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Terms and Conditions</h3>
	</div>
	<div class="modal-body">
		<p>By using Cafe Variome, you are agreeing to the following:</p>
		<div class="well">
			<p>Copyright (c) 2014, University of Leicester</p>
			<p>All rights reserved.</p>
			<p>This license is a legal agreement between you and the University of Leicester for the use of Cafe Variome in-a-box (the "Software"). By obtaining the Software you agree to comply with the terms and conditions of this license.</p>
			<p>PERMITTED USE<br />You are permitted to use, copy, modify, and distribute the Software and its documentation, with or without modification, for any purpose, provided that the following conditions are met:</p>
			<ol>
				<li>A copy of this license agreement must be included with the distribution.</li>
				<li>Redistributions of source code must retain the above copyright notice in all source code files.</li>
				<li>Redistributions in binary form must reproduce the above copyright notice in the documentation and/or other materials provided with the distribution.</li>
				<li>Any files that have been modified must carry notices stating the nature of the change and the names of those who changed them.</li>
				<li>Products derived from the Software must include an acknowledgment that they are derived from Cafe Variome in their documentation and/or other materials provided with the distribution.</li>
				<li>Products derived from the Software may not be called "Cafe Variome", nor may "Cafe Variome" appear in their name, without prior written permission from the University of Leicester.</li>
			</ol>
			<p>INDEMNITY<br />You agree to indemnify and hold harmless the authors of the Software and any contributors for any direct, indirect, incidental, or consequential third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the Software, or a violation of any terms of this license.</p>
			<p>DISCLAIMER OF WARRANTY<br />THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE.</p>
			<p>LIMITATIONS OF LIABILITY<br />YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.</p>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>