<?PHP
include('koneksi.php');
header('Access-Control-Allow-Origin: *');

$id = $_POST['id'];

 $sql = "SELECT * FROM `notifikasi` WHERE id='$id' limit 1";


 $hasil =  $conn->query($sql);
    $arr = array();
    $no=1;
        while($r = $hasil->fetch(PDO::FETCH_ASSOC)){
            $nama = $r['judul'];
            $sqlGambar = "SELECT * FROM data_gambar WHERE nama='$nama'";
            $gambar = $conn->query($sqlGambar)->fetch();
            $gambar = $gambar['foto'];
            array_push($arr,['id' => $no,'judul' => $r['judul'],'tanggal' => Indonesia2Tgl($r['tanggal'])." ".$r['jam'],'keterangan' => $r['keterangan'],'image'=>'https://hikvisionindonesia.co.id/android/'.$gambar]);
            //   array_push($arr,['id' => $no,'image' => 'https://images.unsplash.com/photo-1567226475328-9d6baaf565cf?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=400&q=60']);
            
            $no++;
            
        }
     
    $judul = $arr[0]['judul'];
    $keterangan = $arr[0]['keterangan'];
    $tanggal = $arr[0]['tanggal'];
    $image = $arr[0]['image'];
    

function sendMessage($judul,$keterangan,$image){
    $content = array(
        "en" => $keterangan
        );
        
    $headings = array(
        "en" => $judul
    );

    $fields = array(
        'app_id' => "005361d7-6c23-47a0-ab5d-f2120576bbb7",
        'included_segments' => array('All'),
        'data' => array("foo" => "bar"),
        'large_icon' =>"https://hikvisionindonesia.co.id/android/upload/201221033618logo%20apl%20baru%204.png",
        'big_picture'=>$image,
        'contents' => $content,
        'headings'=>$headings 
    );

    $fields = json_encode($fields);
print("\nJSON sent:\n");
print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                               'Authorization: Basic Y2E4MDE4MjktNThjOC00MjM0LWIyZjgtOGUxZDQzMjBkOGNm'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$response = sendMessage($judul,$keterangan,$image);
$return["allresponses"] = $response;
$return = json_encode( $return);
print("\n\nJSON received:\n");
print($return);
print("\n");
?>
