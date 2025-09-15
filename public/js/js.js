$(document).ready( function () {
    $(function(){
        $('#opron').keypress(function(e){
            console.log(e.which)
            if(e.which == 191 || e.which == 193 || e.which == 47){
              return false;
            }
        });
    });
    $(function(){
        $('#opron1').keypress(function(e){
            console.log(e.which)
            if(e.which == 191 || e.which == 193 || e.which == 47){
              return false;
            }
        });
    });
    $(function(){
        $('#opron2').keypress(function(e){
            console.log(e.which)
            if(e.which == 191 || e.which == 193 || e.which == 47){
              return false;
            }
        });
    });
});

$(document).on('click','#call_mLMpromas',function(){
    const status=$(this).attr('status')
    const oprn=$(this).attr('oprn')
    const prona=$(this).attr('prona')
    const nama_supplier=$(this).attr('nama_supplier')
    const stdqu=$(this).attr('stdqu')
    const itype_id=$(this).attr('itype_id')
    const brand_name=$(this).attr('brand_name')
    const pgrup=$(this).attr('pgrup')
    const sgrup_id=$(this).attr('sgrup_id')
    const ssgrup_id=$(this).attr('ssgrup_id')
    const lssgrup=$(this).attr('lssgrup')
    const weigh=$(this).attr('weigh')
    const meast=$(this).attr('meast')
    const measl=$(this).attr('measl')
    const measp=$(this).attr('measp')
    const volum=$(this).attr('volum')
    const abccl=$(this).attr('abccl')
    const capac=$(this).attr('capac')
    const platf=$(this).attr('platf')
    const mstok=$(this).attr('mstok')
    const spnum=$(this).attr('spnum')
    const garansi=$(this).attr('garansi')
    const pbilp=$(this).attr('pbilp')
    const ijtype=$(this).attr('ijtype')
    const id_cls=$(this).attr('id_cls')
    const mproma=$(this).attr('mproma')

    document.getElementById("mproma").value=mproma
    document.getElementById("statusEdit").value=status
    document.getElementById("opronEdit").value=oprn
    document.getElementById("pronaEdit").value=prona
    document.getElementById("nama_supplierEdit").value=nama_supplier
    document.getElementById("stdquEdit").value=stdqu
    document.getElementById("itype_idEdit").value=itype_id
    document.getElementById("brand_nameEdit").value=brand_name
    document.getElementById("pgrupEdit").value=pgrup
    document.getElementById("sgrup_idEdit").value=sgrup_id
    document.getElementById("ssgrup_idEdit").value=ssgrup_id
    document.getElementById("lssgrupEdit").value=lssgrup
    document.getElementById("weighEdit").value=weigh
    document.getElementById("meastEdit").value=meast
    document.getElementById("measlEdit").value=measl
    document.getElementById("measpEdit").value=measp
    document.getElementById("volumEdit").value=volum
    document.getElementById("abcclEdit").value=abccl
    document.getElementById("capacEdit").value=capac
    document.getElementById("platfEdit").value=platf
    document.getElementById("mstokEdit").value=mstok
    document.getElementById("spnumEdit").value=spnum
    document.getElementById("garansiEdit").value=garansi
    document.getElementById("pbilpEdit").value=pbilp
    document.getElementById("ijtypeEdit").value=ijtype
    document.getElementById("id_clsEdit").value=id_cls
    document.getElementById("formUpdatePromas").setAttribute("action","/mpromas/"+mproma)
});

// $(document).on('click','#btn-delete-promas',function(){
//     const mproma= $(this).attr('proma')
//     $.ajax({
//         url:'/mpromas/destroy/'+mproma,
//         success: function(data) {

//         }
//     })
// })

// $(document).on('click','#btn_update_mproma',function(e){
//     e.preventDefault();
//     var value   =   $('#formUpdatePromas').serialize();

//     $.ajax({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         method:'PUT',
//         url:'/mpromas',
//         data:value,
//         success:function(res){
//             location.reload();
//         }
//     });
// });

$(document).on('click','#call_mLMsreno',function(){
    const id=$(this).attr('idSR')
    const braco=$(this).attr('braco')
    const sreno=$(this).attr('sreno')
    const srena=$(this).attr('srena')
    const steam=$(this).attr('steam')
    const address=$(this).attr('address')
    const phone=$(this).attr('phone')
    const grade=$(this).attr('grade')
    const aktif=$(this).attr('aktif')

    document.getElementById("bracoEdit").value=braco
    document.getElementById("srenoEdit").value=sreno
    document.getElementById("srenaEdit").value=srena
    document.getElementById("steamEdit").value=steam
    document.getElementById("addressEdit").value=address
    document.getElementById("phoneEdit").value=phone
    document.getElementById("gradeEdit").value=grade
    document.getElementById("aktifEdit").value=aktif
    document.getElementById("formUpdateMsreno").setAttribute("action","/msreno/"+id)
});

$(document).on('click','#call_mLMcindu',function(){
    const cindu=$(this).attr('cindu')
    const descr_cindu=$(this).attr('descr_cindu')

    document.getElementById("descr_cinduEdit").value=descr_cindu
    document.getElementById("formUpdateMcindu").setAttribute("action","/mcindu/"+cindu)
});

$(document).on('click','#call_mLMbranch',function(){
    const braco=$(this).attr('braco')
    const brana=$(this).attr('brana')
    const conam=$(this).attr('conam')
    const address=$(this).attr('address')
    const contactp=$(this).attr('contactp')
    const phone=$(this).attr('phone')
    const faxno=$(this).attr('faxno')
    const npwp=$(this).attr('npwp')
    const tglsk=$(this).attr('tglsk')
    const email=$(this).attr('email')


    document.getElementById("bracoEdit").value=braco
    document.getElementById("branaEdit").value=brana
    document.getElementById("conamEdit").value=conam
    document.getElementById("addressEdit").value=address
    document.getElementById("contactpEdit").value=contactp
    document.getElementById("phoneEdit").value=phone
    document.getElementById("faxnoEdit").value=faxno
    document.getElementById("npwpEdit").value=npwp
    document.getElementById("tglskEdit").value=tglsk
    document.getElementById("emailEdit").value=email
    document.getElementById("formUpdateMbranch").setAttribute("action","/mbranch/"+braco)
});

$(document).on('click','#call_mLmdepo',function(){
    const depo=$(this).attr('depo')
    const name=$(this).attr('name')
    const braco=$(this).attr('braco')
    const address=$(this).attr('address')
    const cont=$(this).attr('cont')
    const email=$(this).attr('email')
    const phone=$(this).attr('phone')
    const faxno=$(this).attr('faxno')
    const npwp=$(this).attr('npwp')
    const tglsk=$(this).attr('tglsk')

    document.getElementById("depoEdit").value=depo
    document.getElementById("nameEdit").value=name
    document.getElementById("addressEdit").value=address
    document.getElementById("contEdit").value=cont
    document.getElementById("emailEdit").value=email
    document.getElementById("phoneEdit").value=phone
    document.getElementById("faxnoEdit").value=faxno
    document.getElementById("npwpEdit").value=npwp
    document.getElementById("tglskEdit").value=tglsk

    $.ajax({
        url:'/mdepo/getBranch/'+braco,
        success: function(data2) {
            document.getElementById("bracoEdit").innerHTML=data2
        }
    })

    document.getElementById("formUpdatemdepo").setAttribute("action","/mdepo/"+depo)
});

$(document).on('focusin','#bracoEdit',function(){
    document.getElementById("bracoEdit").innerHTML=""
    $.ajax({
        url:'/mdepo/branche',
        success: function(data) {
            document.getElementById("bracoEdit").innerHTML=data
        }
    })
});

$(document).on('click','#call_mLMstmas',function(){
    const id=$(this).attr('i_d')
    const braco=$(this).attr('braco')
    const cusno=$(this).attr('cusno')
    const shpto=$(this).attr('shpto')
    const shpnm=$(this).attr('shpnm')
    const deliveryaddress=$(this).attr('deliveryaddress')
    const phone=$(this).attr('phone')
    const fax=$(this).attr('fax')
    const contp=$(this).attr('contp')
    const province=$(this).attr('province')
    const kabupaten=$(this).attr('kabupaten')

    document.getElementById("bracoEdit").value=braco
    document.getElementById("cusnoEdit").value=cusno
    document.getElementById("shptoEdit").value=shpto
    document.getElementById("shpnmEdit").value=shpnm
    document.getElementById("deliveryaddressEdit").value=deliveryaddress
    document.getElementById("phoneEdit").value=phone
    document.getElementById("faxEdit").value=fax
    document.getElementById("contpEdit").value=contp

    $.ajax({
        url:'/mstmas/getProvinsi/'+province,
        success: function(data) {
            document.getElementById("provinceEdit").innerHTML=data
        }
    })
    $.ajax({
        url:'/mstmas/getKabKot/'+kabupaten,
        success: function(data2) {
            document.getElementById("kabupatenEdit").innerHTML=data2
        }
    })
    // document.getElementById("provinceEdit").value=province; document.getElementById("kabupatenEdit").value=kabupaten
    document.getElementById("formUpdateMstmas").setAttribute("action","/mstmas/"+id)
});

const bracoEditCusmas=document.getElementById('bracoEditCusmas')
const titleCusmas= document.getElementById('titleEditCusmas')
const cinduCusmas= document.getElementById('cinduEditCusmas')
const czoneCusmas= document.getElementById('czoneEditCusmas')
const careaCusmas= document.getElementById('careaEditCusmas')
if(bracoEditCusmas){
    $.ajax({
        url:'/mdepo/branche',
        success: function(data) {
            document.getElementById("bracoEditCusmas").innerHTML=data
        }
    })
    $.ajax({
        url:'/cusmas/titleCusmas',
        success: function(data) {
            document.getElementById("titleEditCusmas").innerHTML=data
        }
    })
    $.ajax({
        url:'/cusmas/cinduCusmas',
        success: function(data) {
            document.getElementById("cinduEditCusmas").innerHTML=data
        }
    })
    $.ajax({
        url:'/cusmas/czoneCusmas',
        success: function(data) {
            document.getElementById("czoneEditCusmas").innerHTML=data
        }
    })
    $.ajax({
        url:'/cusmas/careaCusmas',
        success: function(data) {
            document.getElementById("careaEditCusmas").innerHTML=data
        }
    })
}

$(document).on('click','#btn_edit_cusmas',function(){
    const id=$(this).attr('i_d')
    const cusno=$(this).attr('cusno')
    const braco=$(this).attr('braco')
    const cusna=$(this).attr('cusna')
    const billn=$(this).attr('billn')
    const title=$(this).attr('title')
    const prpos=$(this).attr('prpos')
    const pkp=$(this).attr('pkp')
    const npwp=$(this).attr('npwp')
    const address=$(this).attr('address')
    const city=$(this).attr('city')
    const kodepost=$(this).attr('kodepost')
    const phone=$(this).attr('phone')
    const fax=$(this).attr('fax')
    const contact=$(this).attr('contact')
    const email=$(this).attr('email')
    const topay=$(this).attr('topay')
    const cindu=$(this).attr('cindu')
    const czone=$(this).attr('czone')
    const carea=$(this).attr('carea')
    const dopen=$(this).attr('dopen')
    const crlim=$(this).attr('crlim')
    const status=$(this).attr('status')
    const barval=$(this).attr('barval')
    const openo=$(this).attr('openo')
    const oarval=$(this).attr('oarval')
    const csect=$(this).attr('csect')

    document.getElementById("span_id_pelanggan").textContent="ID Pelanggan "+id
    document.getElementById("cusnoEditCusmas").value=cusno
    document.getElementById("bracoEditCusmas").value=braco
    $('#bracoEditCusmas').val(braco);
    document.getElementById("cusnaEditCusmas").value=cusna
    document.getElementById("billnEditCusmas").value=billn
    document.getElementById("titleEditCusmas").value=title
    document.getElementById("prposEditCusmas").value=prpos
    document.getElementById("pkpEditCusmas").value=pkp
    document.getElementById("npwpEditCusmas").value=npwp
    document.getElementById("addressEditCusmas").value=address
    document.getElementById("cityEditCusmas").value=city
    document.getElementById("kodepostEditCusmas").value=kodepost
    document.getElementById("phoneEditCusmas").value=phone
    document.getElementById("faxEditCusmas").value=fax
    document.getElementById("contactEditCusmas").value=contact
    document.getElementById("emailEditCusmas").value=email
    document.getElementById("topayEditCusmas").value=topay
    document.getElementById("cinduEditCusmas").value=parseInt(cindu)
    document.getElementById("czoneEditCusmas").value=czone
    document.getElementById("careaEditCusmas").value=parseInt(carea)
    document.getElementById("dopenEditCusmas").value=dopen
    document.getElementById("crlimEditCusmas").value=crlim
    document.getElementById("statusEditCusmas").value=status
    document.getElementById("barvalEditCusmas").value=barval
    document.getElementById("openoEditCusmas").value=openo
    document.getElementById("oarvalEditCusmas").value=oarval
    document.getElementById("csectEditCusmas").value=csect


    // $.ajax({
    //     url:'/mstmas/getProvinsi/'+province,
    //     success: function(data) {
    //         document.getElementById("provinceEdit").innerHTML=data
    //     }
    // })
    // $.ajax({
    //     url:'/mstmas/getKabKot/'+kabupaten,
    //     success: function(data2) {
    //         document.getElementById("kabupatenEdit").innerHTML=data2
    //     }
    // })
    document.getElementById("formUpdateCusmas").setAttribute("action","/cusmas/"+id)
});

$(document).on('change','#province',function(){
    const id=$('#province').find(":selected").val(); console.log(id);
    $.ajax({
        url:'/mstmas/kabkot/'+id,
        success: function(data) {
            document.getElementById("kabupaten").innerHTML=data
        }
    })
});

$(document).on('focusin','#provinceEdit',function(){
    document.getElementById("kabupatenEdit").innerHTML=""
    $.ajax({
        url:'/mstmas/provinsii',
        success: function(data) {
            document.getElementById("provinceEdit").innerHTML=data
        }
    })
});

$(document).on('change','#provinceEdit',function(){
    const id=$('#provinceEdit').find(":selected").val();
    $.ajax({
        url:'/mstmas/kabkot/'+id,
        success: function(data) {
            document.getElementById("kabupatenEdit").innerHTML=data
        }
    })
});

$(document).on('focusin','#groupp',function(){
    document.getElementById("groupp").innerHTML=""
    $.ajax({
        url:'/mcusmas/grup',
        success: function(data) {
            document.getElementById("groupp").innerHTML=data
        }
    })
});
$(document).on('focusin','#nama_perusahaan',function(){
    document.getElementById("nama_perusahaan").innerHTML=""
    $.ajax({
        url:'/mcusmas/customer',
        success: function(data) {
            document.getElementById("nama_perusahaan").innerHTML=data
        }
    })
});

$(document).on('focusin','#provinsi',function(){
    document.getElementById("provinsi").innerHTML=""
    document.getElementById("kabupaten").innerHTML=""
    $.ajax({
        url:'/mcusmas/provinsi',
        success: function(data) {
            document.getElementById("provinsi").innerHTML=data
        }
    })
});
$(document).on('change','#provinsi',function(){
    const prov= $(this).val();
    document.getElementById("kabupaten").innerHTML=""
    $.ajax({
        url:'/mstmas/kabkot/'+ prov,
        success: function(data) {
            document.getElementById("kabupaten").innerHTML=data
        }
    })
});

$(document).on('focusin','#provinsiCusMas',function(){
    document.getElementById("provinsiCusMas").innerHTML=""
    document.getElementById("kabupatenCusMas").innerHTML=""
    $.ajax({
        url:'/mcusmas/provinsi',
        success: function(data) {
            document.getElementById("provinsiCusMas").innerHTML=data
        }
    })
});
$(document).on('change','#provinsiCusMas',function(){
    const prov= $(this).val();
    document.getElementById("kabupatenCusMas").innerHTML=""
    $.ajax({
        url:'/mstmas/kabkot/'+ prov,
        success: function(data) {
            document.getElementById("kabupatenCusMas").innerHTML=data
        }
    })
});

$(document).on('focusin','#bracoMcusmas',function(){
    document.getElementById("bracoMcusmas").innerHTML=""
    document.getElementById("depoMcusmas").innerHTML=""
    $.ajax({
        url:'/mdepo/branche',
        success: function(data) {
            document.getElementById("bracoMcusmas").innerHTML=data
        }
    })
});

$(document).on('focusin','#cabang',function(){
    document.getElementById("cabang").innerHTML=""
    $.ajax({
        url:'/mdepo/branche',
        success: function(data) {
            document.getElementById("cabang").innerHTML=data
        }
    })
});

$(document).on('change','#bracoMcusmas',function(){
    document.getElementById("depoMcusmas").innerHTML=""
    const id= $(this).val();
    $.ajax({
        url:'/mcusmas/getDepo/'+ id,
        success: function(data) {
            document.getElementById("depoMcusmas").innerHTML=data
        }
    })
});
const cusmasAddModal= document.getElementById("mTMcusmas")
if(cusmasAddModal){
    cusmasAddModal.addEventListener('shown.bs.modal', function () {
        document.getElementById('groupp').focus()
    })
}
$(document).on("click","#tambahKabKotCusMas",function(){
    const cusId= document.getElementById("customer_id_edit_cusmas").value
    document.getElementById("customer_idCusMas").value=cusId
    $("#mTSiteCusMas").modal('show');
    //document.getElementById('').focus()
})

$(document).on("click","#tambahMesinCusMas",function(){
    const cusId= document.getElementById("customer_id_edit_cusmas").value
    document.getElementById("customer_idCusMasmesin").value=cusId
    $("#mTMesinCusMas").modal('show');
    //document.getElementById('').focus()
})

const idCusMas= document.getElementById("customer_id_edit_cusmas")
if(idCusMas){
    $.ajax({
        url:'/mcusmas/getSite/'+ idCusMas.value,
        success: function(data) {
            $('#table_site_cusmas').html(data)
            new DataTable('#myTable12', { responsive: true });
        }
    })
}
if(idCusMas){
    $.ajax({
        url:'/mcusmas/getMesin/'+ idCusMas.value,
        success: function(data) {
            $('#table_timbangan_cusmas').html(data)
            new DataTable('#myTable13', { responsive: true });
        }
    })
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

$(document).on('focusout','#oprons',function(){
    const opron= $(this).val();
    $.ajax({
        headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method:"POST",
        url:"/mpromas/cekOpron",
        data:{opron:opron},
        success:function(i){
            if(i>0){
                alert('NOMOR SUDAH PERNAH DISIMPAN!');
                document.getElementById('oprons').value=""
                document.getElementById('oprons').focus()
            }
        }
    })
})

$('.editUser').on('shown.bs.modal', function () {
    $('#nama_edit').focus()
})

// $(document).on('focusin','#nama_perusahaan_oc',function(){
//     document.getElementById("nama_perusahaan_oc").innerHTML=""
//     $.ajax({
//         url:'/mcusmas/customer',
//         success: function(data) {
//             document.getElementById("nama_perusahaan_oc").innerHTML=data
//         }
//     })
// });

const cusOc= document.getElementById('nama_perusahaan_oc')
if(cusOc){
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url:'/roce/customerOc',
        success: function(data) {
            document.getElementById("nama_perusahaan_oc").innerHTML=data
        }
    })

    cusOc.addEventListener("focusout", function(){ const nama=this.value
        if(nama){
            const id= $('#customer_ [value="' + nama + '"]').data('value'); console.log('id: '+id)
            document.getElementById("nama_perusahaan_fix").value= id
        }else{
            document.getElementById("nama_perusahaan_fix").value= ""
        }

    })
}

const renoOc= document.getElementById("msrenos_oc")
if(renoOc){
    $.ajax({
        url:'/roce/renoOc',
        success: function(data) {
            document.getElementById("msrenos_oc").innerHTML=data
        }
    })
    renoOc.addEventListener("change", function(){ const nama=this.value
        if(nama){
            const id= $('#msrenosoc [value="' + nama + '"]').data('value'); console.log('id: '+id)
            document.getElementById("msrenos_fix").value= id
            nama_=nama.split('~')[0]
            document.getElementById("msrenos_oc").value=nama_.slice(0,-1);
        }else{
            document.getElementById("msrenos_fix").value= ""
        }

    })
}

const pgrupOc= document.getElementById("grup_produk_oc")
if(pgrupOc){
    $.ajax({
        url:'/roce/pgrupOc',
        success: function(data) {
            document.getElementById("grup_produk_oc").innerHTML=data
        }
    })

    pgrupOc.addEventListener("change", function(){ const nama=this.value; console.log('group '+nama)
        if(nama){
            document.getElementById("grup_produk_oc").value=nama
        }else{
            document.getElementById("grup_produk_oc").value= "";
        }
    })
}

const clsOc= document.getElementById("cls_oc")
if(clsOc){
    $.ajax({
        url:'/roce/clsOc',
        success: function(data) {
            document.getElementById("cls_oc").innerHTML=data
        }
    })
    clsOc.addEventListener("change", function(){ const nama=this.value; console.log('cls '+nama)
        if(nama){
            document.getElementById("cls_oc").value=nama
        }else{
            document.getElementById("cls_oc").value= "";
        }

    })
}

$('.form_oc').on('keydown', 'input', function (event) { console.log(event.which);
    if (event.which == 13) {
        event.preventDefault();
        var $this = $(event.target);
        var index = parseFloat($this.attr('tabindex'));
        $('[tabindex="' + (index + 1).toString() + '"]').focus();
    }
});

// $(document).on('focusout','#nama_perusahaan',function(){
//     const groupp= document.getElementById("groupp").value
//     const customer= document.getElementById("nama_perusahaan").value
//     $.ajax({
//         url:'/mcusmas/customer',
//         success: function(res) {
//             if(res>0){
//                 document.getElementById("nama_perusahaan").value=""
//             }

//         }
//     })
// });

// let table = new DataTable('#myTable',{
//     responsive: true
// });
// let table2= new DataTable('#myTable2',{
//     responsive: true
// })
// let table3= new DataTable('#myTable3',{
//     responsive: true
// })
// let table4= new DataTable('#myTable4',{
//     responsive: true
// })
// let table5= new DataTable('#myTable5',{
//     responsive: true
// })
// let table6= new DataTable('#myTable6',{
//     responsive: true
// })
// let table7= new DataTable('#myTable7',{
//     responsive: true
// })
// let table8= new DataTable('#myTable8',{
//     responsive: true
// })
// let table9= new DataTable('#myTable9',{
//     responsive: true
// })
// let table10= new DataTable('#myTable10',{
//     responsive: true
// })

new DataTable('#example', {
    responsive: true
});
new DataTable('#myTable', {
    responsive: true
});
new DataTable('#myTable2', {
    responsive: true
});
new DataTable('#myTable3', {
    responsive: true
});
new DataTable('#myTable4', {
    responsive: true
});
new DataTable('#myTable5', {
    responsive: true
});
new DataTable('#myTable6', {
    responsive: true
});
new DataTable('#myTable7', {
    responsive: true
});
new DataTable('#myTable8', {
    responsive: true
});
new DataTable('#myTable9', {
    responsive: true
});
new DataTable('#myTable10', {
    responsive: true
});
new DataTable('#myTable11', {
    responsive: true
});
new DataTable('#myTable12', {
    responsive: true
});
new DataTable('#myTable13', {
    responsive: true
});
new DataTable('#myTable14', {
    responsive: true
});
new DataTable('#myTable15', {
    responsive: true
});
new DataTable('#myTable16', {
    responsive: true
});
new DataTable('#myTable17', {
    responsive: true
});
new DataTable('#myTable18', {
    responsive: true
});
new DataTable('#myTable19', {
    responsive: true
});
new DataTable('#myTable20', {
    responsive: true
});
new DataTable('#myTable21', {
    responsive: true
});
new DataTable('#myTable22', {
    responsive: true
});
new DataTable('#myTable23', {
    responsive: true
});
new DataTable('#myTable24', {
    responsive: true
});
new DataTable('#myTable25', {
    responsive: true
});

// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });
// $(document).ready(function() {
//     $('#example').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '{{route("mpromas/listJson")}}',
//             type: "POST",
//             data: function(data) {
//                 data.cSearch = $("#search").val();
//             }
//         },
//         order: ['1', 'DESC'],
//         pageLength: 10,
//         searching: false,
//         aoColumns: [
//             {
//                 data: 'opron',
//             },
//             {
//                 data: 'prona',
//             },
//             {
//                 data: 'brand_name',
//             },
//             {
//                 data: 'sgrup_id',
//                 width: "20%",
//             },
//             {
//                 data: 'opron',
//                 width: "20%",
//                 render: function(data, type, row) {
//                     return `<a href="${row.opron}">View</a>`;
//                 }
//             }
//         ]
//     });
// });

// $("#searchBtn").click(function(){
//     $('#example').DataTable().ajax.reload();
// })

// let tambahMssgrup= document.getElementById("tambahMssgrup")
// if(tambahMssgrup){
//     tambahMssgrup.addEventListener('click',()=>{

//     })
// }

$(document).on('click','#del_cabang_eacd',function(){
    const id= document.getElementById('del_cabang_eacd').getAttribute("id_del");
    $.ajax({
        url:'/mcusmascab/destroy/'+id,
        success: function(data) {

        }
    })
});

$('#mTMssgrup').on('shown.bs.modal', function() {
    $('#descr_ssgrup').focus();
})
$('#mTMsgrup').on('shown.bs.modal', function() {
    $('#sgrup_id').focus();
})
$('#mTMpgrup').on('shown.bs.modal', function() {
    $('#pgrup').focus();
})
$('#mTMbrand').on('shown.bs.modal', function() {
    $('#brand_name').focus();
})
$('#mTMitype').on('shown.bs.modal', function() {
    $('#itype_id').focus();
})
$('#mTMcls').on('shown.bs.modal', function() {
    $('#id_cls').focus();
})
$('#mTMpromas').on('shown.bs.modal', function() {
    $('#status').focus();
})
$('#mTMsreno').on('shown.bs.modal', function() {
    $('#braco').focus();
})
$('#mTMcindu').on('shown.bs.modal', function() {
    $('#descr_cindu').focus();
})
$('#mTMstmas').on('shown.bs.modal', function() {
    $('#braco').focus();
})
$('#mTMbranch').on('shown.bs.modal', function() {
    $('#braco').focus();
})
$('#mTMdepo').on('shown.bs.modal', function() {
    $('#depo').focus();
})
$('#mTMcusmas').on('shown.bs.modal', function() {
    $('#groupp').focus();
})
$('#mTSiteCusMas').on('shown.bs.modal', function() {
    $('#lokasiCusMas').focus();
})
$('#mTUser').on('shown.bs.modal', function() {
    $('#username').focus();
})
$('#mLUser').on('shown.bs.modal', function() {
    $('#nama_edit').focus();
})

