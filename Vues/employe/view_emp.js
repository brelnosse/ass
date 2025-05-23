const viewMode = document.querySelector(".view-mode");
const editMode = document.querySelector(".edit-mode");
const editBtn = document.querySelector("#edit-btn");
const cancelBtn = document.querySelector("#cancel-btn");

editBtn.addEventListener('click', function(){
    viewMode.style.display = "none";
    editMode.style.display = "block";
})
cancelBtn.addEventListener('click', function(){
    viewMode.style.display = "block";
    editMode.style.display = "none";
})