<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Polygon 02</title>
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <style type="text/css">
    html {
        height: 100%
    }
    body {
        height:100%;
        margin:0;
        padding:0;
        font-family:tahoma, "Microsoft Sans Serif", sans-serif, Verdana;
        font-size:12px;
    }
    /* css กำหนดความกว้าง ความสูงของแผนที่ */
    #map_canvas {
        position:relative;
        width:550px;
        height:400px;
        margin:auto;/*  margin-top:100px;*/
    }
    #contain_map {
        position:relative;
        width:550px;
        height:400px;
        margin:auto;
        margin-top:10px;
    }
    </style>
</head>
<body>
     
   <br>
   <div class="container-fluid">
        <div id="contain_map">
          <div id="map_canvas"></div>
        </div>
                
      
    </div>
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>   
<script type="text/javascript">
  
var map; // กำหนดตัวแปร map ไว้ด้านนอกฟังก์ชัน เพื่อให้สามารถเรียกใช้งาน จากส่วนอื่นได้
var GGM; // กำหนดตัวแปร GGM ไว้เก็บ google.maps Object จะได้เรียกใช้งานได้ง่ายขึ้น
 
var polygon = [];
var marker=[];
var infowindow=[]; 
 
var simple_path = [
    [
        { lat:13.749056356171124, lng:100.62678337097168 },
        { lat:13.7470554365528, lng:100.6442928314209 },
        { lat:13.75972763865473, lng:100.64995765686035 },
        { lat:13.759060697754025, lng:100.63261985778809 }
    ],
    [
        { lat:13.76439617172456, lng:100.66025733947754 },
        { lat:13.753058144112895, lng:100.66523551940918 },
        { lat:13.759227433157418, lng:100.68600654602051 },
        { lat:13.77156552321108, lng:100.67965507507324 }   
    ]   
];
// กำหนด style ของ polygon กรณีเมาส์เลื่อนออก
var polygonOptions_out = {
  strokeColor: '#FF0000',
  geodesic:true,
  strokeOpacity: 1.0,
  strokeWeight: 3,
  fillColor: '#FF0000',
  fillOpacity: 0.35   
}
// กำหนด style ของ polygon กรณีเมาส์อยู่ด้านบน
var polygonOptions_over = {
  strokeColor: '#008000',
  geodesic:true,
  strokeOpacity: 1.0,
  strokeWeight: 3,
  fillColor: '#008000',
  fillOpacity: 0.35   
}
// กำหนด style object เป็น array 
var polygonOptions = [polygonOptions_out,polygonOptions_over];
 
function initialize() { // ฟังก์ชันแสดงแผนที่
    GGM=new Object(google.maps); // เก็บตัวแปร google.maps Object ไว้ในตัวแปร GGM
    // กำหนดจุดเริ่มต้นของแผนที่
    var my_Latlng  = new GGM.LatLng(13.761728449950002,100.6527900695800);
    var my_mapTypeId=GGM.MapTypeId.ROADMAP; // กำหนดรูปแบบแผนที่ที่แสดง
    // กำหนด DOM object ที่จะเอาแผนที่ไปแสดง ที่นี้คือ div id=map_canvas
    var my_DivObj=$("#map_canvas")[0]; 
    // กำหนด Option ของแผนที่
    var myOptions = {
        zoom: 13, // กำหนดขนาดการ zoom
        center: my_Latlng , // กำหนดจุดกึ่งกลาง
        mapTypeId:my_mapTypeId // กำหนดรูปแบบแผนที่
    };
    map = new GGM.Map(my_DivObj,myOptions);// สร้างแผนที่และเก็บตัวแปรไว้ในชื่อ map
 
    // เพิ่มฟังก์ชั่นสำหรับหาตำแหน่งตรงกลางของพื้นที่ polygon
    GGM.Polygon.prototype.my_getBounds=function(){
        var bounds = new google.maps.LatLngBounds()
        this.getPath().forEach(function(element,index){bounds.extend(element)})
        return bounds
    }
 
    // ทดสอบวนลูปสร้าง polygon
     
    for(i = 0; i < simple_path.length; i++){
        polygon[i] = new GGM.Polygon(polygonOptions[0]);
        polygon[i].idx = i; // สร้าง object สำหรับเก็บ index ของ polygon ไว้ใช้กับ infowindow
        polygon[i].setPath(simple_path[i]);
        polygon[i].setMap(map);     
         
        infowindow[i] = new GGM.InfoWindow({// สร้าง infowindow ของแต่ละ เป็นแบบ array
            content: 'test at polygon '+i // ทดสอบแสดงค่า infowindow
        });     
         
        GGM.event.addListener(polygon[i],'mouseover', function(e) { 
            this.setOptions(polygonOptions[1]);
            infowindow[this.idx].setPosition(this.my_getBounds().getCenter());
            infowindow[this.idx].open(map);
       });      
        GGM.event.addListener(polygon[i],'mouseout', function(e) {  
            this.setOptions(polygonOptions[0]);
            infowindow[this.idx].close();
       });             
    }
    
}
$(function(){
    // โหลด สคริป google map api เมื่อเว็บโหลดเรียบร้อยแล้ว
    // ค่าตัวแปร ที่ส่งไปในไฟล์ google map api
    // v=3.2&sensor=false&language=th&callback=initialize
    //  v เวอร์ชัน่ 3.2
    //  sensor กำหนดให้สามารถแสดงตำแหน่งทำเปิดแผนที่อยู่ได้ เหมาะสำหรับมือถือ ปกติใช้ false
    //  language ภาษา th ,en เป็นต้น
    //  callback ให้เรียกใช้ฟังก์ชันแสดง แผนที่ initialize
    $("<script/>", {  
      "type": "text/javascript",  
      src: "http://maps.google.com/maps/api/js?key=AIzaSyATk0ZiHZoPYaSPtoYUB7pOVrDjMd8_eKw&language=th&region=TH&v=3.2&sensor=false&callback=initialize" 
    }).appendTo("body");        
});
</script>      
</body>
</html>
