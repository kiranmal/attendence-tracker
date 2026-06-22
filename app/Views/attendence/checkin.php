<form method="POST" action="/pcl_attendence_sheet/public/employee/checkin.php">


    <input
        type="hidden"
        id="latitude"
        name="latitude">

    <input
        type="hidden"
        id="longitude"
        name="longitude">

    <button
        type="submit"
        id="checkinBtn">
        Getting Location...
    </button>

</form>


<script>
    navigator.geolocation.getCurrentPosition(
        function(position) {

            document.getElementById("latitude").value =
                position.coords.latitude;

            document.getElementById("longitude").value =
                position.coords.longitude;

            document.getElementById("checkinBtn").disabled = false;
            document.getElementById("checkinBtn").innerText = "Check In";

            console.log("Location loaded");
            console.log(document.getElementById("latitude").value);
            console.log(document.getElementById("longitude").value);

        },
        function(error) {

            alert("Location error: " + error.message);

        }
    );
</script>