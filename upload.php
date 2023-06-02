<?php 
include_once('header.php');

?>
<div class="wrapper">
    <div class="container-fluid">
        <?php 
            if(isset($flag)) {
                var_dump($flag);
                if($flag=="error1") {
                ?>
                <br>
                <div class="alert alert-danger mb-0" role="alert">
                    Invalid file format. Only PDF files are allowed.
                </div>
        <?php
                }
                elseif($flag=="error2") {
        ?>
                <br>
                <div class="alert alert-danger mb-0" role="alert">
                    Please choose a PDF file to upload.
                </div>
        <?php
                }
                else {
        ?>
                <br>
                <div class="alert alert-danger mb-0" role="alert">
                    Error uploading the file.
                </div>
        <?php
                }
            }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Form Uploads</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="m-b-30">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title:</label>
                                    <input class="col-sm-10 form-control" type="text" name="title" id="title" required><br>
                                </div>

                                <div class="form-group row">
                                    <label for="pdfFile" class="col-sm-2 col-form-label">PDF File:</label>
                                    <input class="col-sm-10" type="file" name="pdfFile" id="pdfFile" accept=".pdf" required><br>
                                </div>
                                <div class="text-center m-t-15">
                                    <input class="btn btn-primary waves-effect waves-light" type="submit" name="submit" value="Upload">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include_once('conn.php');
    require('assets/pdf2text/class.pdf2text.php');
    global $link;
    $targetDirectory = "pdf/";
    $allowedExtensions = array('pdf');

    if (isset($_POST['submit'])) {
        // Get the uploaded file information
        $file = $_FILES['pdfFile'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        if (!empty($fileName)) {
            $targetFilePath = $targetDirectory . $fileName;
            $fileExtension = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
            // Check if the file has a valid extension
            if (in_array($fileExtension, $allowedExtensions)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                    $a = new PDF2Text();
                    $a->setFilename($targetFilePath);
                    $a->decodePDF();
                    $fileContent = mysqli_real_escape_string($link,$a->output());

                    $title = $_POST['title'];
                    $title = mysqli_real_escape_string($link, $_POST['title']);

                    $insert_query ="INSERT INTO `pdfs`(title, content, file_path) VALUES ('$title','$fileContent', '$targetFilePath')";
                    $insert_recs = mysqli_query($link,$insert_query);
                    
                    // Execute the SQL statement
                    if ($insert_recs) {
                        $flag = "error";
                    } else {
                        $flag = "error";
                    }
                } else {
                    $flag = "error";
                }
            } else {
                $flag = "error1";
            }
        } else {
            $flag = "error2";
        }
    }
    $conn->close();
    ?>
<?php 
include_once('footer.php');
?>