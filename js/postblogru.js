			jQuery(document).ready(function() {

				
				$('#postblogru_send_thank_btn').click(function() {
					$.ajax(
						"http://feedback.bogutsky.ru/public/addthank",
						{
							dataType: 'jsonp',
							data: {project: 'postblogru', url: $('#postblogru_send_url').val(),email: $('#postblogru_send_email').val()},
							success: function(response) {
								alert(response.status);
							}
						}
					);
				});
				
				$('#postblogru_show_responseform').click(function() {
					
					$('#postblogru_responseform').toggle('500');
				});
				
				$('#postblogru_send_response_btn').click(function() {
					if($('#postblogru_send_response').val() != '')
						$.ajax(
							"http://feedback.bogutsky.ru/public/addresponse",
							{
								dataType: 'jsonp',
								data: {project: 'postblogru', response: $('#postblogru_send_response').val(), url: $('#postblogru_send_url').val(),email: $('#postblogru_send_email').val()},
								success: function(response) {
									alert(response.status);
								}
							}
						);
					else
						alert('Сообщение не введено! \n Message is empty!');
				});				
				
				

			});