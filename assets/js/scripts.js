// Generic scripts placeholder
console.log("Fleet Management System loaded");

document.addEventListener('DOMContentLoaded',()=>{
  const toggle=document.getElementById('sidebarToggle');
  if(toggle){
    toggle.addEventListener('click',()=>{
      document.body.classList.toggle('sidebar-open');
    });
  }
});

$(function(){
  if($.fn.DataTable){
    $('.datatable').DataTable({pageLength:25,order:[]});
  }
});