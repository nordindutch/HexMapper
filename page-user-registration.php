<?php

get_header();

?>
<div class="user-registration">
    <div class="registration-container">
    <h1>Sign up here</h1>
        <div class="registration-form">
            <div class="full-width">
                <label for="username">Username</label>
                <input type="text" id="username" maxlength="24" name="username" required>
            </div>
            <div class="full-width">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div  class="half">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div  class="half">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password"  autocomplete="off" required>
            </div>
            <div class="half">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name"  autocomplete="on" required>
            </div>
            <div class="half">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name"  autocomplete="on" required>
            </div>
            <div>
                <input type="submit" id="register-user" value="Submit" name="submit-user">
            </div>
        </div>
    </div>

</div>
<?php

get_footer();
