const btnCandidat = document.querySelector("#btnCandidat");
const btnRecruteur = document.querySelector("#btnRecruteur");
const btnCandidatMini = document.querySelector("#btnCandidatMini");
const btnRecruteurMini = document.querySelector("#btnRecruteurMini");
const formCandidat = document.querySelector("form[name=candidate]");
const formRecruteur = document.querySelector("form[name=recruiter]");
const btnContactCandidate = document.querySelector("#btnContactCandidat");
const btnContactRecruiter = document.querySelector("#btnContactRecruiter");


btnCandidat.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnRecruteur.classList.remove("btnRegisterSelected");
    formCandidat.classList.remove("d-none");
    formRecruteur.classList.add("d-none");
});
btnRecruteur.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnCandidat.classList.remove("btnRegisterSelected");
    formCandidat.classList.add("d-none");
    formRecruteur.classList.remove("d-none");
});
btnCandidatMini.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnRecruteur.classList.remove("btnRegisterSelected");
    formCandidat.classList.remove("d-none");
    formRecruteur.classList.add("d-none");
});
btnRecruteurMini.addEventListener('click', function (e) {
    e.currentTarget.classList.add("btnRegisterSelected");
    btnCandidat.classList.remove("btnRegisterSelected");
    formCandidat.classList.add("d-none");
    formRecruteur.classList.remove("d-none");
});

btnContactCandidate.addEventListener('click', function (e){

});
btnContactRecruiter.addEventListener('click', function (e){

});

