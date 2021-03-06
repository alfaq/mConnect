<!DOCTYPE html>
<html>
<head>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/base64.js"></script>
    <script src="js/aes.js"></script>
    <script src="js/aes-ctr.js"></script>
</head>
<body>

<div>
    <input type="hidden" name="posturl" value="https://sandbox.mintroute.com/voucher/api/category/" id="posturl">
    <div id="categorydiv">
        <div class="form-group">
            <label>Username</label>
            <input type="text" id="username" placeholder="Username" name="username" value="payment-arab-provider-2">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" placeholder="Password" name="password" value="test123">
        </div>
        <div class="form-group">
            <label>Public Token</label>
            <input type="text" id="publictoken" placeholder="Public Token" name="publictoken" value="HK6coxaP">
        </div>
        <div class="form-group">
            <label>Private Token</label>
            <input type="password" id="privatetoken" placeholder="Private Token For Encryption Purpose" value="9f846f5425cc1c6826f70db27a410d77" name="privatetoken">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default" id="categoryrequestbutton">Request Categories</button>
        </div>
    </div>
</div>
<div class="list-group">Result:<br /></div>
<br /><br />
Callback:
<div class="resultdiv"></div>

<script>
    $('#categoryrequestbutton').click(function(){
        $('.list-group').append('<a href="#">API Call Process Started .</a><br />');
        $('.list-group').append('<a href="#">Getting Data from The filled FORM and converting to json.</a><br />');
        var datastring = [];
        datastring = {"username":$('#categorydiv #username').val() , "password": $('#categorydiv #password').val()} ;
        var jsonstring = JSON.stringify(datastring);
        $('.list-group').append('<a href="#">JSON String created from the data <br /> '+jsonstring+'</a><br />');
        $('.list-group').append('<a href="#">Encrypting the data using the private key for secure Transportation <br /> '+jsonstring+'</a><br />');
        var encryptedstring = Aes.Ctr.encrypt(jsonstring,$('#categorydiv #privatetoken').val(), 256);
        var encodedtoken = Base64.encode($('#categorydiv #publictoken').val());
        $('.list-group').append('<a href="#">Encrypted Above given json to the following string <br /> '+encryptedstring+'</a><br />');
        $('.list-group').append('<a href="#">Adding the Following Encrypted String to an argument that will be passed to the MConnect API<br /> postedinfo='+encryptedstring+'</a><br />');
        $('.list-group').append('<a href="#">Encoding the Public Token with Base64 Encoding so '+$('#categorydiv #publictoken').val()+' becomes as follows<br /> token='+encodedtoken+'</a><br />');
        $('.list-group').append('<a href="#">Adding the Base64 Encoded Public Token as an argument that will be passed to the MConnect API<br /> token='+encodedtoken+'</a><br />');
        $('.list-group').append('<a href="#">Compiling the Complete URL that will be passed to the MConnect API<br /> '+$('#posturl').val()+'?postedinfo='+encryptedstring+'&token='+encodedtoken+'</a><br />');
        $('.list-group').append('<a href="#">Making the POST Request to the API as given below <br /> '+$('#posturl').val()+'?postedinfo='+encryptedstring+'&token='+encodedtoken+'</a><br />');
        $.ajax({
            async: false,
            type: 'POST',
            dataType:'jsonp',
            url: $('#posturl').val(),
            data:{postedinfo:encryptedstring,token:encodedtoken},

            success: function(data){
                /*
                if(data.success == false)
                {
                    alert(data.error);
                    $('.list-group').append('<a href="#">Received Error Message ( '+data.error+' ) from the API by posting the following information <br /> '+$('#posturl').val()+'?postedinfo='+encryptedstring+'&token='+$('#categorydiv #publictoken').val()+'</a>');
                    $('.resultdiv').html(data.error)
                    return false;
                }
                alert('tut');
                $('.list-group').append('<a href="#">Received Result ( '+JSON.stringify(data)+' ) from the API by posting the following information <br /> '+$('#posturl').val()+'?postedinfo='+encryptedstring+'&token='+$('#categorydiv #publictoken').val()+'</a>');
                var optionstr = '<option value="">Select Category</option>';
                for(var i=0; i<data.length; i++)
                {
                    currdata = data[i];
                    optionstr += '<option value="'+currdata.category_id+'">'+currdata.category_name+'</option>';
                }
                $('#brandsdiv #categories').empty();
                $('#denomdiv #categories').empty();
                $('#voucherdiv #vouchercategories').empty();

                $('#brandsdiv #categories').append(optionstr);
                $('#denomdiv #categories').append(optionstr);
                $('#voucherdiv #vouchercategories').append(optionstr);*/
                $('.resultdiv').html(JSON.stringify(data));
            },
            error: function(jqXHR, textStatus, errorThrown){
                var error = jqXHR.responseJSON.error
                alert(error);
                $('.list-group').append('<a href="#">Received Error Message ( '+JSON.stringify(jqXHR.responseJSON)+' ) from the API by posting the following information <br /> '+$('#posturl').val()+'?postedinfo='+encryptedstring+'&token='+encodedtoken+'</a>');
                $('.resultdiv').html(error)
                return false;
            }
        });

    });
</script>
</body>
</html>