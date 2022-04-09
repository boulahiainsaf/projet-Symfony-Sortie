let userListLink = document.getElementById("admin-user-list-link");
let userAddLink = document.getElementById("admin-user-add-link");
let userUploadLink = document.getElementById("admin-user-upload-link");
let userListSection = document.getElementById("admin-user-list");
let userAddSection = document.getElementById("admin-user-add");
let userUploadSection = document.getElementById("admin-user-upload");

userListLink && userListLink.addEventListener("click", function(){
    userListLink.className="active";
    userAddLink.className="";
    userUploadLink.className="";
    userListSection.className="show";
    userAddSection.className="hide";
    userUploadSection.className="hide";
});
userAddLink && userAddLink.addEventListener("click", function(){
    userListLink.className="";
    userAddLink.className="active";
    userUploadLink.className="";
    userListSection.className="hide";
    userAddSection.className="show";
    userUploadSection.className="hide";
});
userUploadLink && userUploadLink.addEventListener("click", function(){
    userListLink.className="";
    userAddLink.className="";
    userUploadLink.className="active";
    userListSection.className="hide";
    userAddSection.className="hide";
    userUploadSection.className="show";
});
document.addEventListener("DOMContentLoaded", function () {
    function onClickRemoveUser(event) {
        event.preventDefault();
        const location = window.location.toString();
        const locationBase = location.replace('dashboard', '');
        const id = event.currentTarget.dataset.id;
        const url = locationBase+`delete-user/${id}`;
        const options = {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
                if(data.id && data.status === 'succes'){
                    document.getElementById(`user-${id}`).remove();
                    if(document.querySelector('.flash')) {
                        flash=document.querySelector('.flash');
                        flash.className = 'flash flash-succes alert-dismissible';
                        flash.innerHTML=
                            `${data.message}`+
                            '<button type="button" id="close-flash-ajax" class="close" data-dismiss="flash" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>';
                        flash.style.display = 'block';
                        flash.style.opacity = '1';
                    }else{
                        main = document.querySelector('main');
                        flash = document.createElement("div");
                        flash.className = 'flash flash-succes alert-dismissible';
                        flash.innerHTML=
                            `${data.message}`+
                            '<button type="button" id="close-flash-ajax" class="close" data-dismiss="flash" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>';
                        main.insertBefore(flash, main.firstChild);
                    }
                    let fade = setInterval(function () {
                        if (!flash.style.opacity) {
                            flash.style.opacity = 1;
                        }
                        if (flash.style.opacity > 0) {
                            flash.style.opacity -= 0.05;
                        }
                        else {
                            clearInterval(fade);
                            flash.style.display = "none";
                        }
                    }, 300);
                }else{
                    if(document.querySelector('.flash')) {
                        flash=document.querySelector('.flash');
                        flash.className = 'flash flash-echec alert-dismissible';
                        flash.innerHTML=
                            `${data.message}`+
                            '<button type="button" id="close-flash-ajax" class="close" data-dismiss="flash" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>';
                        flash.style.display = 'block';
                        flash.style.opacity = '1';
                    }else{
                        main = document.querySelector('main');
                        flash = document.createElement("div");
                        flash.className = 'flash flash-echec alert-dismissible';
                        flash.innerHTML=
                            `${data.message}`+
                            '<button type="button" id="close-flash-ajax" class="close" data-dismiss="flash" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>';
                        main.insertBefore(flash, main.firstChild);
                    }
                    let fade = setInterval(function () {
                        if (!flash.style.opacity) {
                            flash.style.opacity = 1;
                        }
                        if (flash.style.opacity > 0) {
                            flash.style.opacity -= 0.05;
                        }
                        else {
                            clearInterval(fade);
                            flash.style.display = "none";
                        }
                    }, 300);
                }
            })
            .catch(error=>{
                console.log(error);
                throw(error);
            });
    }


    function onClickChangeStatus(event) {
        event.preventDefault();
        const location = window.location.toString();
        const locationBase = location.replace('dashboard', '');
        const id = event.currentTarget.dataset.id;
        const url = locationBase+`update-user/${id}`;
        const options = {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
                if(data.id && data.status === true) {
                    document.getElementById(`status-${id}`).innerText = 'X';
                    document.getElementById(`actions-${id}`).innerText = ' • Désactiver';
                    document.getElementById(`actions-${id}`).title = 'Désactiver';
                }else {
                    document.getElementById(`status-${id}`).innerText = '';
                    document.getElementById(`actions-${id}`).innerText = ' • Activer';
                    document.getElementById(`actions-${id}`).title = 'Activer';
                }
            })
            .catch(error=>{
                console.log(error);
                throw(error);
            });
    }

    let userArray = [];
    function onClickRemoveUsers(event){
        event.preventDefault();
        // userArray.push();
        let checkbox=document.querySelectorAll(".checkbox-user");
        for(let i=0; i<checkbox.length; i++){
            if(checkbox[i].type=='checkbox' && checkbox[i].checked===true){
                userArray.push(checkbox[i].dataset.id);
            }
        }
        const dataSend = new FormData();
        //dataSend.append('users', userArray.toString());
        dataSend.append('users', userArray);
        const location = window.location.toString();
        const locationBase = location.replace('dashboard', '');
        const url = locationBase+`delete-users`;
        const options = {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
            body:dataSend
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
               console.log(data);
              //TODO recuperer des ids à supprimer et ceux qui ne peuvent pas être supprimés
            })
            .catch(error=>{
                console.log(error);
                throw(error);
            });

        console.log(userArray);
        userArray = [];
    }
    /***********************************************************
     ***************** CODE PRINCIPAL
     ***********************************************************/
    document.querySelectorAll(".delete-user-link").forEach((user) => {
        user.addEventListener("click", onClickRemoveUser);
    });

    document.querySelectorAll(".change-status-link").forEach((user) => {
        user.addEventListener("click", onClickChangeStatus);
    });

    document.getElementById('user-search-reset').addEventListener("click", function(){
        // #user-search-input .search-bar
        console.log(document.querySelector("#user-search-input .search-bar").value);
        document.querySelector("#user-search-input .search-bar").setAttribute('value', '');
    });

    /*document.querySelectorAll(".checkbox-user").forEach((chbox) => {
        chbox.addEventListener("click", function(){
            let form = document.querySelector("#user-list-wrapper form");
            if(!document.querySelector('.remove-users')) {
                let removeBtn = document.createElement("button");
                removeBtn.className = 'btn remove-users';
                removeBtn.type = 'button';
                removeBtn.id='remove-btn-all-users';
                removeBtn.innerText = 'Suppimer selectionés';
                form.appendChild(removeBtn);
            }
        });
    });*/

    if(document.getElementById('remove-btn-all-users')){
        document.getElementById('remove-btn-all-users').addEventListener("click", onClickRemoveUsers);
    }



});