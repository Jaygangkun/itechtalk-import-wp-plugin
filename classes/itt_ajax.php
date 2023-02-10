<?php

class ITT_Ajax
{
    private $api;
    public function __construct() {
        add_action( 'wp_ajax_itt_import', [$this, 'import'] );
    }
    
    public function set_api($api) {
        $this->api = $api;
    }

    public function import() {
        $csvFile = ITT_UPLOADS_PATH.'csv/'.time().'.csv';

        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $csvFile)) {
            echo json_encode([
                'success' => false,
                'message' => 'uploading file failed!'
            ]); die();
        }

        $arrData = [];
        $file = fopen($csvFile, 'r');
        $isHeaderSkip = false;
        while (($line = fgetcsv($file)) !== FALSE) {
            if(!$isHeaderSkip) {
                $isHeaderSkip = true;
                continue;
            }
            $arrData[] = $line;
        }
        fclose($file);

        foreach($arrData as $data) {
            $dataTitle = empty($data[0]) ? '' : trim($data[0]);
            $dataNetwork = empty($data[1]) ? '' : $data[1];
            $dataLaunch = empty($data[2]) ? '' : $data[2];
            $dataBody = empty($data[3]) ? '' : $data[3];
            $dataDisplay = empty($data[4]) ? '' : $data[4];
            $dataPlatform = empty($data[5]) ? '' : $data[5];
            $dataMemory = empty($data[6]) ? '' : $data[6];
            $dataMainCamera = empty($data[7]) ? '' : $data[7];
            $dataSelfieCamera = empty($data[8]) ? '' : $data[8];
            $dataCamera = empty($data[9]) ? '' : $data[9];
            $dataSound = empty($data[10]) ? '' : $data[10];
            $dataComms = empty($data[11]) ? '' : $data[11];
            $dataFeatures = empty($data[12]) ? '' : $data[12];
            $dataBattery = empty($data[13]) ? '' : $data[13];
            $dataMisc = empty($data[14]) ? '' : $data[14];
            $dataTests = empty($data[15]) ? '' : $data[15];
            $dataItemURL = empty($data[16]) ? '' : $data[16];
            $dataImageURL = empty($data[17]) ? '' : $data[17];
            $dataType = empty($data[18]) ? '' : $data[18];

            $existIttProductID = post_exists($dataTitle, '', '', ITT_POST_TYPE, 'publish');
            if(!$existIttProductID) {
                $ittProductID = wp_insert_post([
                    'post_type' => ITT_POST_TYPE,
                    'post_title' => $data[0],
                    'post_status' => 'publish'
                ]);
            }
            else {
                $ittProductID = $existIttProductID;
            }
            
            update_post_meta($ittProductID, 'network', $dataNetwork);
            update_post_meta($ittProductID, 'launch', $dataLaunch);
            update_post_meta($ittProductID, 'body', $dataBody);
            update_post_meta($ittProductID, 'display', $dataDisplay);
            update_post_meta($ittProductID, 'platform', $dataPlatform);
            update_post_meta($ittProductID, 'memory', $dataMemory);
            update_post_meta($ittProductID, 'main_camera', $dataMainCamera);
            update_post_meta($ittProductID, 'selfie_camera', $dataSelfieCamera);
            update_post_meta($ittProductID, 'camera', $dataCamera);
            update_post_meta($ittProductID, 'sound', $dataSound);
            update_post_meta($ittProductID, 'comms', $dataComms);
            update_post_meta($ittProductID, 'features', $dataFeatures);
            update_post_meta($ittProductID, 'battery', $dataBattery);
            update_post_meta($ittProductID, 'misc', $dataMisc);
            update_post_meta($ittProductID, 'tests', $dataTests);
            update_post_meta($ittProductID, 'item_url', $dataItemURL);
            update_post_meta($ittProductID, 'image_url', $dataImageURL);
            update_post_meta($ittProductID, 'type', $dataType);

            if(!empty($dataImageURL)) {
                $imageData = file_get_contents($dataImageURL);
                $imageExt = pathinfo($dataImageURL, PATHINFO_EXTENSION);
                $imageName = basename($dataImageURL);
                // $imageFile = ITT_UPLOADS_PATH.'image/'.time().'.'.$imageExt;
                $upload_dir = wp_upload_dir();
                if( wp_mkdir_p( $upload_dir['path'] ) ) {
                    $imageFile = $upload_dir['path'] . '/' . $imageName;
                } else {
                    $imageFile = $upload_dir['basedir'] . '/' . $imageName;
                }

                file_put_contents( $imageFile, $imageData );

                // Check image file type
                $wp_filetype = wp_check_filetype( $imageName, null );

                // Set attachment data
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title'     => sanitize_file_name( $imageName ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Create the attachment
                $attach_id = wp_insert_attachment( $attachment, $imageFile, $ittProductID );

                // Include image.php
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Define attachment metadata
                $attach_data = wp_generate_attachment_metadata( $attach_id, $imageFile );

                // Assign metadata to attachment
                wp_update_attachment_metadata( $attach_id, $attach_data );

                // And finally assign featured image to post
                set_post_thumbnail( $ittProductID, $attach_id );

            }

        }

        echo json_encode([
            'success' => true,
            'message' => 'uploaded '.count($arrData).' records'
        ]); die();
    }
}