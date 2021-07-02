const listCauses = document.querySelectorAll("#listCauses li");
const elemCauses = document.querySelector("#listCauses");
const elemCausesRecruiter = document.querySelector("#listCausesRecruiter");
const listCausesRecruiter = document.querySelectorAll("#listCausesRecruiter li");
const formCauseRecruiter = document.querySelector("#recognition");
const btnValideFormCauseRecruiter = document.querySelector("#btnAjoutCauseRecruiter");
const btnAnnulerFormCauseRecruiter = document.querySelector("#btnAnnulerAjoutCauseRecruiter");
const elemDescriptionFormulaire = formCauseRecruiter.querySelector('#recognition_description');
const elemTextFormulaire = formCauseRecruiter.querySelector('#recognition_text');
const formOne = document.querySelector("#formOne");
const elemNameFormulaire = document.querySelector("#nameForm");


// const titreSelection = document.querySelector("#titre_selection");
formCauseRecruiter.classList.add("d-none");
btnValideFormCauseRecruiter.classList.add("d-none");
btnAnnulerFormCauseRecruiter.classList.add("d-none");
formOne.classList.add("d-none");

// traitement de la liste des actions de gauche
listCauses.forEach(function (item) {
    item.addEventListener('click', function (e) {
        clickCause(e);
    })
});

// fonction clic sur un élément de gauche
function clickCause(e) {
    e.preventDefault();
    // on rend inactive la liste de gauche
    listCauses.forEach(function (item) {
        item.classList.toggle("disabled");
    });
    // on initialise l'action choisie
    idCause = e.currentTarget.getAttribute('data-id');
    e.currentTarget.setAttribute('data-selected', true);
    // on vide les champs du formulaire
    idText = e.currentTarget.getAttribute('data-text');
    elemNameFormulaire.innerHTML = 'Cause : ' + e.currentTarget.getAttribute('data-name');
    // on rend visible le formulaire
    formCauseRecruiter.classList.remove("d-none");
    btnValideFormCauseRecruiter.classList.remove("d-none");
    btnAnnulerFormCauseRecruiter.classList.remove("d-none");
    formOne.classList.remove("d-none");
    //titreSelection.innerHTML = idText;
}

// traitement de la liste des actions de droite
listCausesRecruiter.forEach(function (item) {
    item.addEventListener('click', function (e) {
        clickCauseRecruiter(e);
    });
});

function clickCauseRecruiter(e) {
    // on rend inactive la liste de droite
    listCausesRecruiter.forEach(function (item) {
        item.classList.toggle("disabled");
    });

    fetch(baseURL + "/recruiter/api/recruiter/recognition/delete", {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            'id_cause': parseInt(e.currentTarget.getAttribute("data-id")),
        })
    }).then(response => response.json())
        .then(response => {
            if (response.status >= 200 && response.status < 300) {
            } else {
                // cas où l'API a retourné une erreur
                throw response.message;
            }
        })
        .catch(e => {
            alert(e);
        })
    listCausesRecruiter.forEach(function (item) {
        item.classList.toggle("disabled");
    });
    // on supprime l'élément de la liste de droite
    e.currentTarget.remove();
    // on ajoute l'élement dans la liste de gauche
    ajoutLiCause(e.currentTarget.getAttribute("data-id-cause"),
        e.currentTarget.getAttribute("data-name-cause"))
    //elemActions.querySelector(`li[data-id="${idAction}"]`).classList.toggle("d-none");
}

// traitement du submit du formulaire
btnValideFormCauseRecruiter.addEventListener('click', function (e) {
    e.preventDefault();
    if (elemDescriptionFormulaire.value === '' || elemTextFormulaire.value === ''){
        alert('Ce champ ne peut être vide ') // todo :: appelé ici !!!
    }
    else {
        submitCauseRecruiter();
    }
})
btnAnnulerFormCauseRecruiter.addEventListener('click', function (e) {
    e.preventDefault();
    formCauseRecruiter.classList.add('d-none');
    formOne.classList.add('d-none');
    btnAnnulerFormCauseRecruiter.classList.add('d-none');
    btnValideFormCauseRecruiter.classList.add('d-none');
    // on rend active la liste de gauche
    listCauses.forEach(function (item) {
        item.classList.toggle("disabled");
    });
})

function ajoutLiCauseRecruiter(item) {
    // on ajoute un nouvel élement dans la liste des options du cadre droite
    let li = document.createElement('li');
    li.innerHTML = `
                        <button class="btn-recognition text-center">-</button>
                        <span>${item.cause.text}</span><br>
                        ${item.description}
                    `;
    li.setAttribute('class', "list-group-item");
    li.setAttribute("data-id", item.id);
    li.setAttribute("data-id-cause", item.cause.id);
    li.setAttribute("data-name-cause", item.cause.text);
    li.addEventListener('click', function (e) {
        clickCauseRecruiter(e);
    });
    elemCausesRecruiter.appendChild(li);
}

function ajoutLiCause(id, name) {

    let li = document.createElement('li');
    li.innerHTML = `
                        <button class="btn-recognition text-center">+</button>
                        <span>${name}</span><br>
                    `;
    li.setAttribute('class', "list-group-item");
    li.setAttribute("data-id", id);
    li.setAttribute("data-text", name);
    li.addEventListener('click', function (e) {
        clickCause(e);
    });
    elemCauses.appendChild(li);
}

function submitCauseRecruiter() {

    // appel AJAX pour l'ajout de l'action pour le recruteur
    fetch(baseURL + "/recruiter/api/recruiter/recognition/add", { // todo : voir le controller
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            'recruiter': recruiter,
            'cause': parseInt(idCause),
            'description': elemDescriptionFormulaire.value, // todo : ici !!!
            'text': elemTextFormulaire.value
        })
    })
        .then(response => response.json())
        .then(response => {
            if (response.status >= 200 && response.status < 300) {
                // on récupère l'action de gauche sélectionnée
                const elemCauseSelected = document.querySelector('#listCauses li[data-selected]');
                // on ajoute un nouvel élement dans la liste des options du cadre droit
                ajoutLiCauseRecruiter(response.data);
                // on rend active la liste de gauche
                listCauses.forEach(function (item) {
                    item.classList.toggle("disabled");
                });
                // on cache l'action du cadre de gauche qui a été sélectionné
                elemCauseSelected.removeAttribute('data-selected');
                elemCauseSelected.classList.toggle('d-none');
                // on cache le formulaire
                formCauseRecruiter.classList.add("d-none");
                formOne.classList.add("d-none");
                btnValideFormCauseRecruiter.classList.add("d-none");
                btnAnnulerFormCauseRecruiter.classList.add("d-none");
            } else {
                // cas où l'API a retourné une erreur
                throw response.message;
            }
        })
        .catch(e => {
            alert(e);
        })
    ;
}