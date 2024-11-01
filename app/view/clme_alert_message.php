<?php
// ok #04AA6D
// errer #f44336
    function  clme_alert_message ($alert,$color){
?>
        <div class='clme_alert'>
            <span class="clme_closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            <strong>WorkChat: </strong> <?php echo esc_html( $alert ) ?>
        </div>
        
        <style>
            .clme_alert {
            position: fixed;
            top: 40px;
            left: 500px;
            padding: 20px;
            background-color: <?php echo( esc_attr($color)) ?>;
            color: white;
            border-radius: 5px;
            }

            .clme_closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
            }

            .clme_closebtn:hover {
            color: black;
            }
        </style>
        <script>
        document.querySelector('.clme_closebtn').onclick = function(){
            document.querySelector('.clme_alert').style.display='none';
        };
        </script>
<?php
    }
