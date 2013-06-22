<?php 
echo $this->Form->create('User', array("action"=>"login"));
?>
<div class="inner-content">
    <div class="welcome-box">
        <div class="row-cell">
            <?php 
            echo $this->Form->input("email");
            echo $this->Form->input("password");
            ?>
            <a href="<?php echo APP_BASE . "users/forgot"; ?>">Forgot?</a>
            <div class="submit">
                <input type="submit" value="Login" />
            </div>
        </div>
        <div class="row-cell">
            
        </div>
    </div>
    
</div>
<?php echo $this->Form->end();