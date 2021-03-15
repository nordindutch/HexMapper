jQuery(document).ready(function($) {
    console.log('Locked and loaded')
    let contenteditable = false;

    // Retrieve Hex

    // Register User
    $(document).on('click', '#register-user', function(event) {
        event.preventDefault();
        let filledIn = true;
        $('.registration-form>div>input').removeClass('too_short no_match');
        $('.registration-form>div .message').remove();
        $('.registration-form>div>input').each(function() {
            if (!$(this).val()) {
                filledIn = false;
            }
        })
        if (filledIn) {
            if ($('#username').val().length >= 3) {
                if ($('#password').val() == $('#confirm_password').val() && $('#password').val().length >= 8) {
                    $.ajax({
                        url: getHex.ajax_url,
                        type: 'post',
                        data: {
                            action: 'register_user',
                            username: $('#username').val(),
                            password: $('#password').val(),
                            email: $('#email').val(),
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                        },
                        beforeSend: function() {
                            $('.registration-form>div:last-of-type').after("<div class='ajax-loader' style='background-color: black'></div>")
                        },
                        success: function(html) {
                            // What I have to do...
                            $(".ajax-loader").remove();
                            let returned_object = JSON.parse(html);
                            console.log(returned_object)
                            if (returned_object.success) {
                                $(".registration-form").remove();
                                $(".registration-container").append("<div class='registration-form'> Successfully registered, welcome " + returned_object.name + "<br><br>Redirecting to the homepage in 5 seconds </div>");
                                console.log("Successfully registered, welcome " + returned_object.name)
                                setTimeout(function() { document.location.href = "/"; }, 5000)
                            } else {
                                $('.registration-form>div:last-of-type').after("<div class='message'>" + returned_object.error + "</div>")
                            }

                        },
                        fail: function() {
                            // What I have to do...
                            console.log("Ajax request failed")
                        }
                    });
                } else if ($('#password').val() == $('#confirm_password').val()) {
                    $('#password,#confirm_password').addClass('too_short');
                    $('#password').after("<div class='message'>The password is too short</div>");
                    console.log("TOO SHORT")
                } else if ($('#password').val() != $('#confirm_password').val() && $('#password').val().length >= 8) {
                    $('#confirm_password').addClass('no_match');
                    $('#confirm_password').after("<div class='message'>The passwords did not match</div>");
                    console.log("NO MATCH")
                } else {
                    console.log("Not good enough")
                    $('#password').after("<div class='message'>The password did not match and is too short</div>");
                }
            } else {
                $('#username').addClass('too_short');
                $('#username').after("<div class='message'>The username has too be at least 3 characters long</div>");
            }
        } else {
            $('.registration-form>div:last-of-type').after("<div class='message'>All fields must be filled in</div>")
        }
        return false;
    });
    $("input#username").on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });

    $(document).on('click', '.dropdown>.menu-item', function(event) {
        $(this).parent().toggleClass('active')
    })
    $(document).on('click', '.add', function(e) {
        $(this).toggleClass('active');
    })
    $(document).on('click', '.add#hexmap', function(event) {

        $(".create-hexmap").toggleClass('active');
    })
    $('#hexmap-image-upload').on('click touchstart', function() {
        $(this).val('');
    });

    $(document).on('change', '#hexmap-image-upload', function(event) {
        event.preventDefault();
        var form_data = new FormData();
        let file_data = $('#hexmap-image-upload').prop('files')[0];
        if (file_data.size > 2097152) {
            alert("File is too big!");
            $('#hexmap-image-upload').val('');
        } else {
            form_data.append('file', file_data);
            form_data.append('action', 'upload_file');
            $.ajax({
                url: getHex.ajax_url,
                type: 'POST',
                data: form_data,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#upload-form').append('<div class="ajax-loader"></div>');
                    $("#image-upload").addClass("uploaded");
                },
                success: function(html) {
                    let content = JSON.parse(html);
                    $('.ajax-loader').remove();
                    $("#image-upload").attr('data-image', content.id);
                    $("#image-upload").css('background-image', 'url(' + content.url + ')');

                    console.log(html, content.url);
                    $("#image-upload label>span").text("Replace Image");

                },
                error: function() {
                    console.log("Ajax request failed")
                }
            });
        }
    })
    $(document).on('click', '#hexmap-submit', function(e) {
        e.preventDefault();

        let title = $('#hexmap-name').val();
        $.ajax({
            url: getHex.ajax_url,
            type: 'POST',
            data: {
                action: 'create_hexmap',

                title: title,
                img_id: $("#image-upload").attr('data-image')
            },

            beforeSend: function() {
                $("body").css("cursor", "wait");
                $('#hexmap-submit').remove();
                $('.hexmap-submit-container').addClass('loading');
            },
            success: function(html) {
                $("body").css("cursor", "default");
                console.log(html)
                location.reload();
            },
            error: function() {
                $("body").css("cursor", "default");
                console.log("Ajax request failed")
            }
        });
    })

})