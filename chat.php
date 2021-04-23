<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
	Chat
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">


			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle" style="text-align:center">Chat</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>


			<div class="modal-body" width="50%">

				<iframe width="50%" height="40%" class="arrondie" src="chatRafraichi.php"></iframe>
				<iframe scrolling="no" width="100%" height="60%" class="chat" src="chatEnvoiMsg.php"></iframe>
			</div>
		</div>
	</div>
				<script src="jquery/jquery.min.js"></script>
				<!-- JavaScript Boostrap plugin -->
				<script src="bootstrap/js/bootstrap.min.js"></script>