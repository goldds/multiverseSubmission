//console.log(101); //test
var approvedMime = ["application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/msword", "application/pdf"]
function validate (files){
    
    var fileType = files[0].type;
    var user_file = files[0];
    const reader = new FileReader();
    if (approvedMime.includes(fileType)){
       // console.log(101)  //test
        // if previously invalid type was given this would remove the html user feedback and enable the submission

    var insertedContent = document.querySelector(".insertedContent");

    const button = document.getElementById("fileSubmission");

    var valid = '<b class ="insertedContent" style="color: green;"> File type accepted! </b>'
        // remove unneeded inputs if valid input
     var ManualInput= document.querySelector('#manual_input')
        if (ManualInput){
    ManualInput.parentElement.removeChild(ManualInput)};
        //confirmation
    if(insertedContent) {
    insertedContent.parentNode.removeChild(insertedContent)
    button.disabled = false
    };
    button.insertAdjacentHTML("afterend", valid);

    //preview doc
    var preview = document.getElementById('preview')
   
    const src = URL.createObjectURL(files[0]);
     preview.setAttribute("src", src);
    

    }else{
        // submitation is disabled and user feedback is given
        //console.log(01);  // test


        var insertedContent = document.querySelector(".insertedContent");
        if(insertedContent) {
            insertedContent.parentNode.removeChild(insertedContent)};

        const button = document.getElementById("fileSubmission");
        button.disabled = true;

        var invalid = '<b class ="insertedContent" style="color: red;"> Invalid File Type </b>';

        button.insertAdjacentHTML("afterend", invalid);
        
    }}
    


$(".resumeUpload").change(function (event){ // event is based on file selectiong
  //console.log(103); //test
  var control = document.getElementById("resumeUpload");
  var files = control.files;
    validate(files);
    console.log("File uploaded"); 
});
//edit page

$(".replaceUpload").change(function (event){ // event is based on file selectiong
    //console.log(103); //test
    var control = document.getElementById("replaceUpload");
    var files = control.files;
    validate(files);
    console.log("File uploaded"); 
  });
