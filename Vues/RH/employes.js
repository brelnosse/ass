const search = document.querySelector("#search");
const bdate = document.querySelector("#bdate");
const addeddate = document.querySelector("#added_date");
const isAnd = document.querySelector("#isAnd");
const order = document.querySelector("#order");
const status = document.querySelector("#status");
const mailstatus = document.querySelector("#mailstatus");
const trainees = document.querySelector(".trainees");
const cancel = document.querySelector("#cancel");
const resultsInfoFirst = document.querySelector(".results-info .first-part");
const resultsInfoSecond = document.querySelector(".results-info .second-part");
const pagination = document.querySelector(".pagination");

let isAndV = null;

let obj = {
    search: null,
    bdate: null,
    added_date: null,
    ordre: null,
    status: null,
    email_status: null
}
cancel.addEventListener('click', function(e){
    obj.search = null;
    obj.bdate = null;
    obj.added_date = null;
    obj.ordre = null;
    obj.status = null;
    obj.email_status = null;
    bdate.value = "";
    addeddate.value = "";
    order.value = "";
    status.value = "";
    mailstatus.value = "";
    sendRequest();
    cancel.disabled = true;
    isAnd.checked = false;
})
sendRequest();
search.addEventListener('input', function(e){
    updateParam('search', e.target.value.toLowerCase().trim())
    sendRequest();
});

bdate.addEventListener('change', function(e){
    if(e.target.value.trim() != null){
        updateParam('bdate', e.target.value);
        sendRequest();
    }
})

addeddate.addEventListener('change', function(e){
    if(e.target.value.trim() != null){
        updateParam('added_date', e.target.value);
        sendRequest();
    }
})

order.addEventListener('change', function(e){
    if(e.target.value.trim() != null){
        updateParam('ordre', e.target.value);
        sendRequest();
    }

})
status.addEventListener('change', function(e){
    if(e.target.value.trim() != null){
        updateParam('status', e.target.value);
        sendRequest();
    }
})
mailstatus.addEventListener('change', function(e){
    if(e.target.value.trim() != null){
        updateParam('email_status', e.target.value);
        sendRequest();
    }
})
isAnd.addEventListener('change', function(e){
    if(e.target.checked){
        isAndV = true;
        sendRequest();
    }else{
        isAndV = false;
        sendRequest();
    }
})
function getpagination(){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'getpages.php?page', true);
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            pagination.innerHTML = xhr.responseText;
            handlePagination()
        }
    }
    xhr.send(null)
}
function sendRequest(offset=0){
    trainees.innerHTML = "";
    let data = JSON.stringify(obj);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'manageXHR.php?filtres='+data+'&isAnd='+isAndV+'&offset='+offset);
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById('loaderc').style.display = 'none';
            const results = Object.values(JSON.parse(xhr.responseText));
            if(results.length > 1){
                for(let i = 0; i < results.length-1; i++){
                    trainees.innerHTML += `
                        <tr>
                            <td>
                                <a href="../employe/view_emp.php?id=${results[i].id}" class="link dark" style="position: relative">
                                    <span class="status-dot ${(results[i].status_compte == "active") ? "status-online" : "status-offline"}" style="height: 5px; width: 5px; position: absolute; top: 50%;left:5px"></span>
                                    ${results[i].nom}
                                </a>
                            </td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">${results[i].prenom}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">${results[i].email}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">${results[i].auth_key}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">${results[i].poste.toUpperCase()}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">${results[i].added_date}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark">+237 ${results[i].phone}</a></td>
                            <td><a href="../employe/view_emp.php?id=${results[i].id}" class="link dark"><span class="badge ${results[i].received_email != null ? "badge-success" : "badge-danger"}">${results[i].received_email != null ? "Mail envoyé" : "Mail non envoyé"}</span></a></td>
                            <td>
                                <a href="../employe/view_emp.php?id=${results[i].id}" class="status-indicator" class="link dark" style='text-decoration: none; color: #333'>
                                    <span class="status-dot ${(results[i].status != null &&  results[i].status == "connected") ? "status-online" : "status-offline"}"></span>
                                    ${(results[i].status != null &&  results[i].status == "connected") ? "connecté" : "Déconnecté"}
                                </a>
                            </td>
                        </tr>                
                    `;
                }
            }else{
                trainees.innerHTML = `
                    <tr>
                        <td colspan="9" class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="empty-message">
                                <i class="fas fa-info-circle"></i> Aucun élément trouvé
                            </div>
                            <div class="empty-suggestion">
                                Essayez de modifier vos critères de recherche ou de réinitialiser les filtres
                            </div>
                            <a href="" class="btn">
                                <i class="fas fa-redo"></i> Réinitialiser les filtres
                            </a>
                        </td>
                    </tr>
                `;
            }
            resultsInfoFirst.innerHTML = `Affichage de ${results[results.length-1] > 0 ? '1':'0' } à ${results[results.length-1]}`;
            getpagination();
            // console.log(xhr.responseText)
        }else{
            document.getElementById('loaderc').style.display = 'flex';
        }
    }
    xhr.send(null);
}

function handlePagination(){
    const pages = document.querySelectorAll(".page-btn");
    for(let page of pages){
        page.addEventListener('click', function(e){
            if(e.target.textContent.trim() !== '<' && e.target.textContent.trim() !== '>')
                sendRequest((parseInt(e.target.textContent.trim())-1)*10);
            else{
                let activeVal;
                if(e.target.textContent.trim() == '<'){
                    for(let p of pages){    
                        if(p.classList.contains('active')){
                            activeVal = parseInt(p.textContent.trim())-2;
                        }
                    }
                }
                if(e.target.textContent.trim() == '>'){
                    for(let p of pages){    
                        if(p.classList.contains('active')){
                            activeVal = parseInt(p.textContent.trim());
                        }
                    }
                }
                sendRequest(activeVal*10);
            }
        })
    }
}
function updateParam(prop, value){
    obj[prop] = value;
    let i = 0;
    for(let key in obj){
        if(obj[key] != null){
            cancel.disabled = false;
            break;
        }else{
            i++;
        }
    }
    if(i == obj.length){
        cancel.disabled = true;
    }
}