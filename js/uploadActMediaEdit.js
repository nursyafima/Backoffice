const images = ["jpg", "gif", "png", "jpeg"];
const videos = ["mp4", "3gp", "ogg", "mkv", "m4v", "avi", "wmv"];
var user = document.getElementById("email").getAttribute("value");
user.replace("@", "").replace(".", "");
var date = "";
document.getElementById("dateAct").addEventListener("change", function () {
  date = this.value;
});
var files = [];
var urlArrayImg = [];
var urlArrayVid = [];
var fileExt = "";
document.getElementById("files").addEventListener("change", function (e) {
  const files = e.target.files;
  //checks if files are selected
  if (files.length != 0) {
    //Loops through all the selected files
    for (let i = 0; i < files.length; i++) {
      fileExt = fileExtension(files[i].name);
      //create a storage reference
      if (images.includes(fileExt)) {
        var fileName = files[i].name;
        var storage = firebase
          .storage()
          .ref("daycare/" + "activity/" + fileName);
        //upload file
        var upload = storage.put(files[i]);

        //url
        storage.getDownloadURL().then(function (url) {
          urlArrayImg.push(url);
          const imageUrl = document.getElementById("imageUrl");
          imageUrl.setAttribute("value", urlArrayImg.toString().split(","));
          console.log(imageUrl);
        });
      } else if (videos.includes(fileExt)) {
        var fileName = files[i].name;
        //create a storage reference
        var storage = firebase
          .storage()
          .ref("daycare/" + "activity/" + fileName);

        //upload file
        var upload = storage.put(files[i]);

        //url
        storage.getDownloadURL().then(function (url) {
          urlArrayVid.push(url);
          const videoUrl = document.getElementById("videoUrl");
          videoUrl.setAttribute("value", urlArrayVid.toString().split(","));
          console.log(videoUrl);
        });
      }
      // update progress bar
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

        function complete() {}
      );
    }
  } else {
    alert("No file chosen");
  }
});

function fileExtension(filename) {
  var a = filename.toString().split(".").pop();

  return a;
}

// document.getElementById("save").addEventListener("click", function() {

// });

// function getFileUrl(filename) {
//   //create a storage reference
//   var storage = firebase.storage().ref('image/' + filename[i].name);

//   //get file url

// }
