<?php
// echo "<pre>";
// 	print_r($_SERVER);
// 	echo "</pre>";
// 	exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = file_get_contents("php://input");
    $_POST = json_decode($postdata, true);
}

if(isset($_POST['action']) && $_POST['action'] == 'senddata'){
	echo "<pre>";
	print_r($_POST['data']);
	echo "</pre>";
	exit;
}

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>web worker</title>
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<style type="text/css">
	.data-container{
		background-color: #b0b0b0;
		height: 200px;
		width: 400px;
		display: flex;
		justify-content: center;
		align-self: center;
		border-radius: 8px;
		box-shadow: 10px 8px 7px #faf7f7;
	}
	.details{
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
</style>
<body>
	<div class="data-container">
		<div id="fullname-details" class="details">
			<input required pattern="[A-Za-z]+" type="text" name="firstname" id="firstname" value="" placeholder="First Name" class="form-control mb-2">
			<input required pattern="[A-Za-z]+" type="text" name="middlename" id="middlename" value="" placeholder="Middle Name" class="form-control mb-2">
			<input required pattern="[A-Za-z]+" type="text" name="lastname" id="lastname" value="" placeholder="Last Name" class="form-control mb-2">
			<div class="d-flex justify-content-between">
				<input type="button" name="back" id="back_1" value="back" class="btn btn-danger">
				<input type="button" name="confirm" id="confirm_1" value="confirm" class="btn btn-primary" onclick="SendData()">
			</div>
		</div>
	</div>
	<script src="bootstrap.bundle.min.js"></script>
	<script type="text/javascript">	

		function SendData() {
            const fname = document.getElementById('firstname').value;
            const mname = document.getElementById('middlename').value;
            const lname = document.getElementById('lastname').value;

            const data = {
                firstname: fname,
                middlename: mname,
                lastname: lname
            };

            if (navigator.onLine) {
                sendDataToServer(data);
            } else {
                storedatalocally(data);
            }
        }

        function sendDataToServer(data) {
        	//vurl = "action=senddata&data="+JSON.stringify(data);
            axios.post("", {
            	"action":"senddata",
            	"data":JSON.stringify(data)
            }).then(response => {
            	if(response.status == 200){
            		console.log('Data sent to server:', result);
                	clearLocalStorage();
            	}
            }).catch(error => {
                console.error('Error sending data to server:', error);
                storedatalocally(data);
            });
        }

        function storedatalocally(data) {
            let storedData = JSON.parse(localStorage.getItem('formData')) || [];
            storedData.push(data);
            localStorage.setItem('formData', JSON.stringify(storedData));
            console.log('Data stored locally:', JSON.stringify(data));
        }

        function clearLocalStorage() {
            localStorage.removeItem('formData');
        }

        function syncLocalData() {
            const storedData = JSON.parse(localStorage.getItem('formData')) || [];
            storedData.forEach(data => {
                sendDataToServer(data);
            });
        }

        window.addEventListener('online', () => {
            console.log('Browser back to online');
            syncLocalData();
        });

        window.addEventListener('offline', () => {
            console.log('Browser is offline');
        });

        // Initial check for network status
        // if (navigator.onLine) {
        //     syncLocalData();
        // }
	</script>
</body>
</html>