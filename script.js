let role="";
let editIndex = {muat:null, bongkar:null, maintenance:null};

let dataMuat = JSON.parse(localStorage.getItem("muat")) || [];
let dataBongkar = JSON.parse(localStorage.getItem("bongkar")) || [];
let dataMaintenance = JSON.parse(localStorage.getItem("maintenance")) || [];

/* ================= FIX DOM ================= */
const username = document.getElementById("username");
const password = document.getElementById("password");
const error = document.getElementById("error");

const menuAdmin = document.getElementById("menuAdmin");
const menuUser = document.getElementById("menuUser");

const dashboard = document.getElementById("dashboard");
const adminDashboard = document.getElementById("adminDashboard");

const loginPage = document.getElementById("loginPage");
const registerPage = document.getElementById("registerPage");
const app = document.getElementById("app");

const tableMuat = document.getElementById("tableMuat");
const tableBongkar = document.getElementById("tableBongkar");
const tableMaintenance = document.getElementById("tableMaintenance");

const viewMuat = document.getElementById("viewMuat");
const viewBongkar = document.getElementById("viewBongkar");
const viewMaintenance = document.getElementById("viewMaintenance");

/* INPUT */
const platMuat = document.getElementById("platMuat");
const supirMuat = document.getElementById("supirMuat");
const typeUnitMuat = document.getElementById("typeUnitMuat");
const lokasiMuat = document.getElementById("lokasiMuat");
const tanggalMuat = document.getElementById("tanggalMuat");
const dpMuat = document.getElementById("dpMuat");
const fileMuat = document.getElementById("fileMuat");

const platBongkar = document.getElementById("platBongkar");
const supirBongkar = document.getElementById("supirBongkar");
const typeUnitBongkar = document.getElementById("typeUnitBongkar");
const lokasiBongkar = document.getElementById("lokasiBongkar");
const tanggalBongkar = document.getElementById("tanggalBongkar");
const dpBongkar = document.getElementById("dpBongkar");
const fileBongkar = document.getElementById("fileBongkar");

const supirMaintenance = document.getElementById("supirMaintenance");
const platMaintenance = document.getElementById("platMaintenance");
const kendaraan = document.getElementById("kendaraan");
const tanggalService = document.getElementById("tanggalService");
const fileMaintenance = document.getElementById("fileMaintenance");
const keterangan = document.getElementById("keterangan");

const btnMuat = document.getElementById("btnMuat");
const btnBongkar = document.getElementById("btnBongkar");
const btnMaintenance = document.getElementById("btnMaintenance");

/* REGISTER INPUT */
const regUsername = document.getElementById("regUsername");
const regPassword = document.getElementById("regPassword");
const regMsg = document.getElementById("regMsg");

/* ================= UPDATE DASHBOARD ================= */
function updateDashboard(){
    document.getElementById("totalMuat").innerText = dataMuat.length;
    document.getElementById("totalBongkar").innerText = dataBongkar.length;
    document.getElementById("totalMaintenance").innerText = dataMaintenance.length;
}

/* ================= NAVIGASI REGISTER ================= */
function showRegister(){
loginPage.classList.add("hidden");
registerPage.classList.remove("hidden");
}

function backToLogin(){
registerPage.classList.add("hidden");
loginPage.classList.remove("hidden");
}

/* ================= LOGIN ================= */
function login(){

// ADMIN
if(username.value==="admin" && password.value==="123"){
role="admin";
menuAdmin.classList.remove("hidden");
menuUser.classList.add("hidden");
dashboard.classList.add("hidden");
adminDashboard.classList.remove("hidden");
}

// USER DARI STORAGE
else{
let users = JSON.parse(localStorage.getItem("users")) || [];

let found = users.find(u => 
u.username === username.value && u.password === password.value
);

if(found){
role="user";
menuUser.classList.remove("hidden");
menuAdmin.classList.add("hidden");
}else{
error.innerText="Login gagal!";
return;
}
}

loginPage.style.display="none";
registerPage.style.display="none";
app.classList.remove("hidden");

loadData();
updateDashboard();
}

function logout(){location.reload();}

function showPage(p){
document.querySelectorAll('.card').forEach(el=>el.classList.add('hidden'));
document.getElementById(p).classList.remove('hidden');
updateDashboard();
}

function formatRupiah(a){
if(!a) return "-";
return "Rp "+parseInt(a).toLocaleString("id-ID");
}

/* ================= REGISTER ================= */
function register(){
let users = JSON.parse(localStorage.getItem("users")) || [];

if(!regUsername.value || !regPassword.value){
regMsg.style.color="red";
regMsg.innerText="Isi semua field!";
return;
}

let exist = users.find(u => u.username === regUsername.value);
if(exist){
regMsg.style.color="red";
regMsg.innerText="Username sudah ada!";
return;
}

users.push({
username: regUsername.value,
password: regPassword.value
});

localStorage.setItem("users", JSON.stringify(users));

regMsg.style.color="green";
regMsg.innerText="Registrasi berhasil!";

regUsername.value="";
regPassword.value="";
}

/* ================= RESET ================= */
function resetForm(key){
if(key==="muat"){
platMuat.value="";
supirMuat.value="";
typeUnitMuat.value="";
lokasiMuat.value="";
tanggalMuat.value="";
dpMuat.value="";
fileMuat.value="";
btnMuat.innerText="Simpan";
}

if(key==="bongkar"){
platBongkar.value="";
supirBongkar.value="";
typeUnitBongkar.value="";
lokasiBongkar.value="";
tanggalBongkar.value="";
dpBongkar.value="";
fileBongkar.value="";
btnBongkar.innerText="Simpan";
}

if(key==="maintenance"){
supirMaintenance.value="";
platMaintenance.value="";
kendaraan.value="";
tanggalService.value="";
keterangan.value="";
fileMaintenance.value="";
document.querySelectorAll('#maintenance input[type="checkbox"]').forEach(x=>x.checked=false);
btnMaintenance.innerText="Simpan";
}
}

/* ================= SAVE ================= */
function saveData(key, data){
let arr = JSON.parse(localStorage.getItem(key)) || [];

if(editIndex[key] !== null && editIndex[key] >= 0){
arr[editIndex[key]] = data;
editIndex[key] = null;
resetForm(key);
alert("Data berhasil diupdate!");
}else{
arr.push(data);
alert("Data berhasil disimpan!");
}

localStorage.setItem(key, JSON.stringify(arr));
loadData();
updateDashboard();
}

/* ================= LOAD ================= */
function loadData(){
loadTable("muat", tableMuat, viewMuat);
loadTable("bongkar", tableBongkar, viewBongkar);
loadTable("maintenance", tableMaintenance, viewMaintenance);
}

function loadTable(key, userTable, adminTable){
let data = JSON.parse(localStorage.getItem(key)) || [];
userTable.innerHTML="";
adminTable.innerHTML="";

data.forEach((row,i)=>{

let actionUser = `<button onclick="editData('${key}',${i})">Edit</button>`;
let actionAdmin = `
<button onclick="editData('${key}',${i})">Edit</button>
<button onclick="deleteData('${key}',${i})">Hapus</button>`;

let rowUser = row.replace("</tr>", `<td>${actionUser}</td></tr>`);
let rowAdmin = row.replace("</tr>", `<td>${actionAdmin}</td></tr>`);

userTable.innerHTML += rowUser;
adminTable.innerHTML += rowAdmin;

});
}

/* ================= DELETE ================= */
function deleteData(key,index){
let data = JSON.parse(localStorage.getItem(key)) || [];
data.splice(index,1);
localStorage.setItem(key, JSON.stringify(data));
loadData();
updateDashboard();
}

/* ================= EDIT ================= */
function editData(key,index){
let data = JSON.parse(localStorage.getItem(key)) || [];
let row = data[index];

let temp = document.createElement("tr");
temp.innerHTML = row;

let td = temp.querySelectorAll("td");

editIndex[key]=index;

alert("Mode EDIT aktif, klik simpan untuk update data");

if(key==="muat"){
platMuat.value=td[0].innerText;
supirMuat.value=td[1].innerText;
typeUnitMuat.value=td[2].innerText;
lokasiMuat.value=td[3].innerText;
tanggalMuat.value=td[4].innerText;
dpMuat.value=td[5].innerText.replace(/\D/g,'');
btnMuat.innerText="Update Data";
showPage('muat');
}

if(key==="bongkar"){
platBongkar.value=td[0].innerText;
supirBongkar.value=td[1].innerText;
typeUnitBongkar.value=td[2].innerText;
lokasiBongkar.value=td[3].innerText;
tanggalBongkar.value=td[4].innerText;
dpBongkar.value=td[5].innerText.replace(/\D/g,'');
btnBongkar.innerText="Update Data";
showPage('bongkar');
}

if(key==="maintenance"){
supirMaintenance.value=td[0].innerText;
platMaintenance.value=td[1].innerText;
kendaraan.value=td[2].innerText;
tanggalService.value=td[3].innerText;
keterangan.value=td[5].innerText;

document.querySelectorAll('#maintenance input[type="checkbox"]').forEach(x=>{
x.checked=false;
});

document.querySelectorAll('#maintenance .kondisi').forEach(s=>{
s.value="";
s.disabled=true;
});

let checklist = td[4].innerText.split(", ");

checklist.forEach(item=>{
let match = item.match(/(.*)\s\((.*)\)/);

if(match){
let nama = match[1];
let kondisi = match[2];

document.querySelectorAll('#maintenance .checkbox-grid label').forEach(label=>{
let cb = label.querySelector('input[type="checkbox"]');
let sel = label.querySelector('.kondisi');

if(cb.value === nama){
cb.checked = true;
sel.disabled = false;
sel.value = kondisi;
}
});
}
});

btnMaintenance.innerText="Update Data";
showPage('maintenance');
}

/* RESET */
document.querySelectorAll('#maintenance .checkbox-grid label').forEach(label=>{
let check = label.querySelector("input[type='checkbox']");
let select = label.querySelector(".kondisi");
let file = label.querySelector(".file-item");

check.checked = false;
select.value = "";
select.disabled = true;
file.value = "";
file.classList.add("hidden");
});

/* AMBIL DATA DARI TABLE */
let checklist = td[4].innerHTML.split("<br>");

checklist.forEach(item=>{
let match = item.match(/(.*?) \((.*?)\)/);

if(match){
let nama = match[1];
let kondisi = match[2];

document.querySelectorAll('#maintenance .checkbox-grid label').forEach(label=>{
let check = label.querySelector("input[type='checkbox']");
let select = label.querySelector(".kondisi");
let file = label.querySelector(".file-item");

if(check.value === nama){
check.checked = true;
select.disabled = false;
select.value = kondisi;
file.classList.remove("hidden");
}
});
}
});

btnMaintenance.innerText="Update Data";
showPage('maintenance');
}


/* ================= MUAT ================= */
function tambahMuat(){
let data = {
plat: platMuat.value,
supir: supirMuat.value,
type: typeUnitMuat.value,
lokasi: lokasiMuat.value,
tanggal: tanggalMuat.value,
dp: dpMuat.value
};

saveData("muat", data);
updateDashboard();
}

/* ================= BONGKAR ================= */
function tambahBongkar(){
let f=fileBongkar.files,links="";
for(let i=0;i<f.length;i++){
let u=URL.createObjectURL(f[i]);
links+=`<a href="${u}" download>${f[i].name}</a><br>`;
}

let row=`<tr>
<td>${platBongkar.value}</td>
<td>${supirBongkar.value}</td>
<td>${typeUnitBongkar.value}</td>
<td>${lokasiBongkar.value}</td>
<td>${tanggalBongkar.value}</td>
<td>${formatRupiah(dpBongkar.value)}</td>
<td>${links}</td></tr>`;

saveData("bongkar", row);
updateDashboard();
}

/* ================= MAINTENANCE ================= */
function tambahMaintenance(){
if(!supirMaintenance.value || !platMaintenance.value){
alert("Data belum lengkap!");
return;
}

let c=[];

document.querySelectorAll('#maintenance .checkbox-grid label').forEach(label=>{
let checkbox = label.querySelector('input[type="checkbox"]');
let select = label.querySelector('.kondisi');

if(checkbox.checked){
let kondisi = select.value || "Baik";
c.push(checkbox.value + " (" + kondisi + ")");
}
});

let f=fileMaintenance.files,links="";
for(let i=0;i<f.length;i++){
let u=URL.createObjectURL(f[i]);
links+=`<a href="${u}" download>${f[i].name}</a><br>`;
}

let row=`<tr>
<td>${supirMaintenance.value}</td>
<td>${platMaintenance.value}</td>
<td>${kendaraan.value}</td>
<td>${tanggalService.value}</td>
<td>${c.join(", ")}</td>
<td>${keterangan.value}</td>
<td>${links}</td></tr>`;

saveData("maintenance", row);
updateDashboard();
}

/* ================= EXPORT ================= */
function exportExcel(key){
let data = JSON.parse(localStorage.getItem(key)) || [];

if(data.length === 0){
alert("Data kosong!");
return;
}

let table = "<table border='1'><tr>";

if(key==="muat"){
table += "<th>Plat</th><th>Supir</th><th>Type Unit</th><th>Lokasi</th><th>Tanggal</th><th>DP</th>";
}
if(key==="bongkar"){
table += "<th>Plat</th><th>Supir</th><th>Type Unit</th><th>Lokasi</th><th>Tanggal</th><th>Total</th>";
}
if(key==="maintenance"){
table += "<th>Supir</th><th>Plat</th><th>Kendaraan</th><th>Tanggal</th><th>Checklist</th><th>Keterangan</th>";
}

table += "</tr>";

data.forEach(row=>{
let temp = document.createElement("tr");
temp.innerHTML = row;

let td = temp.querySelectorAll("td");

table += "<tr>";

for(let i=0;i<td.length;i++){
if(td[i].innerHTML.includes("<a")) continue;
table += "<td>"+td[i].innerText+"</td>";
}

table += "</tr>";
});

table += "</table>";

let blob = new Blob([table], { type: "application/vnd.ms-excel" });
let url = URL.createObjectURL(blob);

let a = document.createElement("a");
a.href = url;
a.download = key + ".xls";
a.click();
}

/* ================= FILE PER CHECKBOX ================= */
function toggleItem(el){
let parent = el.parentElement;
let select = parent.querySelector(".kondisi");
let file = parent.querySelector(".file-item");

if(el.checked){
select.disabled = false;
file.classList.remove("hidden");
}else{
select.disabled = true;
select.value = "";
file.classList.add("hidden");
file.value = "";
}
}

/* ================= KONDISI ENABLE ================= */
document.querySelectorAll('#maintenance input[type="checkbox"]').forEach(cb=>{
cb.addEventListener('change', function(){
let select = this.parentElement.querySelector('.kondisi');
if(select){
select.disabled = !this.checked;
if(!this.checked) select.value="";
}
});
});
