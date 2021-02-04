jQuery(document).ready(function() {
	jQuery( /*"#notify-me-form"*/ "input[name='wdm-notify-me-button']").click(function(e) {
		e.preventDefault();

				email = jQuery('input[name="email"]').val();
				first_name = jQuery('input[name="first_name"]').val();
				last_name = jQuery('input[name="last_name"]').val();
		
                // url = ajax_data.ajax_url;
                url = "./sendy/subscribe"; 
                action = ajax_data.action_function;

		jQuery.post(url, {
				// action: action,
				email: email,
				name: first_name+''+last_name,
				// list: ajax_data.list_id,
				list: 1,
				boolean: true,


			},
			function(data) {
				if (data) {
					if (data == "Some fields are missing.") {
						jQuery("#subscribed-status").html("<center>Please fill in your email.</center>");
						jQuery("#subscribed-status").css("color", "red");
					} else if (data == "Invalid email address.") {
						jQuery("#subscribed-status").html("<center>Your email address is invalid.</center>");
						jQuery("#subscribed-status").css("color", "red");
					} else if (data == "Invalid list ID.") {
						jQuery("#subscribed-status").html("<center>Your list ID is invalid.</center>");
						jQuery("#subscribed-status").css("color", "red");
					} else if (data == "Already subscribed.") {
						jQuery("#subscribed-status").html("<center>You're already subscribed!</center>");
						jQuery("#subscribed-status").css("color", "red");
					} else {
						jQuery("#subscribed-status").html("<center>You're subscribed successfully!</center>");
						jQuery("#subscribed-status").css("color", "green");

					}
				} else {
					alert("Sorry, unable to subscribe. Please try again later!");
				}
			}
		);
	});
	// jQuery("#signup-form").keypress(function(e) {
	// 	if (e.keyCode == 13) {
	// 		e.preventDefault();
	// 		jQuery(this).submit();
	// 	}
	// });
	// jQuery("input[name='wdm-notify-me-button']").click(function(e) {
	// 	console.log("Hello");
	// 	e.preventDefault();
	// 	jQuery("#notify-me-form").submit();
	// });
});


// $('#data1').animate({left: 0});


jQuery("#data1").animate({ //be sure to change the class of your element to "header"
        left:'250px',
        opacity:'0.5',
        height:'150px',
        width:'150px'
    });
