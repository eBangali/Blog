<script>
if(navigator.geolocation)
{
navigator.geolocation.getCurrentPosition(showPosition, uSererror, {enableHighAccuracy:true,timeout:60000,maximumAge:0});
}
//
function showPosition(position)
{
    var latitudeFromByBrowser = position.coords.latitude;
    var longitudeFromByBrowser = position.coords.longitude;
    
    $(document).ready(function(){ 
        $.ajax({
            type: "POST",
            url: "<?php echo themeResource; ?>/a-common-gps.php",
            data: {
                latitudeFromByBrowser: latitudeFromByBrowser, 
                longitudeFromByBrowser: longitudeFromByBrowser
            },
            dataType: 'json',
            success: function(data){
                //
            },
            error: function(xhr, status, error){
                //
            }
        });
    });
}
//
function uSererror(whicherror)
{
if (whicherror.code==1) { alert("Permission Denied"); }
if (whicherror.code==2) { alert("Network or Satellites Down"); }
if (whicherror.code==3) { alert("GeoLocation timed out"); }
}
</script>
<?php include_once (eblayout.'/a-common-gps.php'); ?>