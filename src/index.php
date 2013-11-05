<form action="signin.php" method="get">
    name:  <input type="text" name="name"><br />
    uid:  <input type="text" name="uid"><br />
    psw:  <input type="password" name="psw"><br />
    address:  <input type="text" name="addr"><br />
    location: <input type="text" name="loc"><br />
    profile:  <input type="file" name="pro"><br />
    sex 
        <input type = "radio" name = "gender" value = "male" >male
        <input type = "radio" name = "gender" value = "female" >female
        <input type = "radio" name = "gender" value = "unknown" >unknown
    <br />
    <input type="hidden" name="action" value="submitted" />
    <input type="submit" name="submit" value="submit me!" />
</form>
