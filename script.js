function validateuserinfo(){
  var emp_no = document.forms["createuser"]["emp_no"].value;
  var username = document.forms["createuser"]["username"].value;
  var password = document.forms["createuser"]["password"].value;
  var first_name = document.forms["createuser"]["first_name"].value;
  var middle_name = document.forms["createuser"]["middle_name"].value;
  var last_name = document.forms["createuser"]["last_name"].value;
  var dept_id = document.forms["createuser"]["dept_id"].value;
  var rank = document.forms["createuser"]["rank"].value;
  var status = document.forms["createuser"]["status"].value;
  var message = "";
  if(emp_no=="" || username=="" || password=="" || first_name=="" || middle_name=="" || last_name=="" || dept_id=="" || rank=="" || status="")
  {
    if(emp_no=="") var message=message+"Employee number field cannot be left blank.\n";
    if(username=="") var message=message+"Username field cannot be left blank.\n";
    if(password=="") var message=message+"Password field cannot be left blank.\n";
    if(first_name=="") var message=message+"First name field cannot be left blank.\n";
    if(middle_name=="") var message=message+"Middle name field cannot be left blank.\n";
    if(last_name=="") var message=message+"Last name field cannot be left blank.\n";
    if(dept_id=="") var message=message+"Department field cannot be left blank.\n";
    if(rank=="") var message=message+"Rank field cannot be left blank.\n";
    if(status=="") var message=message+"Status field cannot be left blank.\n";
    alert(message);
    return false;
  }
}

function validatestudentinfo(){
  var student_no = document.forms["createstudent"]["student_no"].value;
  var first_name = document.forms["createstudent"]["first_name"].value;
  var middle_name = document.forms["createstudent"]["middle_name"].value;
  var last_name = document.forms["createstudent"]["last_name"].value;
  var college_id = document.forms["createstudent"]["college_id"].value;
  var degree = document.forms["createstudent"]["degree"].value;
  var message = "";
  if(student_no=="" || first_name=="" || middle_name=="" || last_name=="" || college_id=="" || degree=="")
  {
    if(student_no=="") var message=message+"Student number field cannot be left blank.\n";
    if(first_name=="") var message=message+"First name field cannot be left blank.\n";
    if(middle_name=="") var message=message+"Middle name field cannot be left blank.\n";
    if(last_name=="") var message=message+"Last name field cannot be left blank.\n";
    if(college_id=="") var message=message+"College field cannot be left blank.\n";
    if(degree=="") var message=message+"Degree field cannot be left blank.\n";
    alert(message);
    return false;
  }
}

function validatecourseinfo(){
  var course_code = document.forms["createcourse"]["course_code"].value;
  var course_title = document.forms["createcourse"]["course_title"].value;
  var units = document.forms["createcourse"]["units"].value;
  var course_description = document.forms["createcourse"]["course_description"].value;
  var message = "";
  /********/
  var arrCheckboxes = document.forms["createcourse"]["sem[]"];
  var checkCount = 0;
  for (var i = 0; i < arrCheckboxes.length; i++)
  {
    checkCount += (arrCheckboxes[i].checked) ? 1 : 0;
  }
  /********/
  if(course_code=="" || course_title=="" || units=="" || course_description=="" || checkCount==0)
  {
    if(course_code=="") var message=message+"Course code field cannot be left blank.\n";
    if(course_title=="") var message=message+"Course title field cannot be left blank.\n";
    if(units=="") var message=message+"Units field cannot be left blank.\n";
    if(course_description=="") var message=message+"Course description field cannot be left blank.\n";
    if(checkCount==0) var message=message+"Semester/s offered field cannot be left blank.\n";
    return false;
  }
}