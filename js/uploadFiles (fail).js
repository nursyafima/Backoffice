var files = [];
var urlArray = [];
document.getElementById("files").addEventListener("change", function (e) {
  const files = e.target.files;
  //checks if files are selected
  if (files.length != 0) {
    //Loops through all the selected files
    for (let i = 0; i < files.length; i++) {
      //create a storage reference
      var fileName = files[i].name;
      var storage = firebase.storage().ref("daycare/profile/" + fileName);

      //upload file
      var upload = storage.put(files[i]);

      //update progress bar
      upload.on(
        "state_changed",
        function progress(snapshot) {
          var percentage =
            (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
          document.getElementById("progress_bar").value = percentage;
        },

        function error() {
          alert("error uploading file");
        },

        function complete() {
          //url
          storage.getDownloadURL().then(function (url) {
            urlArray.push(url);
            console.log(urlArray);
            console.log(urlArray.length);
            const imageUrl = document.getElementById("imageUrl");
            imageUrl.setAttribute("value", urlArray.toString().split(","));
            console.log(imageUrl);
          });
        }
      );
    }
  } else {
    alert("No file chosen");
  }
});

// document.getElementById("save").addEventListener("click", function() {

// });

// function getFileUrl(filename) {
//   //create a storage reference
//   var storage = firebase.storage().ref('image/' + filename[i].name);

//   //get file url

// }
