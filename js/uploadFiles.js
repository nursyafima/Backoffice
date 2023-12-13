var urlArrayImg = [];
//Listen for file selection
document.getElementById("files").addEventListener("change", function (e) {
  //Get files
  for (var i = 0; i < e.target.files.length; i++) {
    var imageFile = e.target.files[i];
    uploadImageAsPromise(imageFile);
  }
});

//Handle waiting to upload each file using promise
function uploadImageAsPromise(imageFile) {
  return new Promise(function (resolve, reject) {
    var storageRef = firebase
      .storage()
      .ref("daycare/profile/" + imageFile.name);

    //Upload file
    var task = storageRef.put(imageFile);

    //Update progress bar
    task.on(
      "state_changed",
      function progress(snapshot) {
        var percentage =
          (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
        document.getElementById("progress_bar").value = percentage;
      },
      function error(err) {},
      function complete() {
        task.snapshot.ref.getDownloadURL().then(function (downloadURL) {
          urlArrayImg.push(downloadURL);
          console.log(downloadURL);
          const imageUrl = document.getElementById("imageUrl");
          imageUrl.setAttribute("value", urlArrayImg.toString().split(","));
          console.log(imageUrl);
        });
      }
    );
  });
}
