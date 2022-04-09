<?php


namespace App\Services;

class Reader
{

    public static function toArray(string $path, $delimiter = ",")
    {
        try {
            if (!file_exists($path) || !is_readable($path)) {
                return FALSE;
            }
            $data = array();
            $lineNumber = 1;
            if (($handle = fopen($path, "r")) !== FALSE) {
                while ($row = fgetcsv($handle, 1000, $delimiter)) {
                    $data[] = $row;
                    $lineNumber++;
                }
                fclose($handle);
            }
        } catch (\Exception $e) {
            var_dump($e);
        }
        return $data;
    }

    /**
     * Take a CSV, and convert into array
     * @param string $path
     * @return array
     */

    public static function CSVtoArray(string $path, $delimiter = ',')
    {
        $ok = true;
        try {
            if (!file_exists($path) || !is_readable($path)) {
                return FALSE;
            }
            $header = array("nom", "prenom", "telephone", "mail", "campus");
            $data = array();
            $message = '';
            $lineNumber = 1;
            if (($handle = fopen($path, "r")) !== FALSE) {
                while ($row = fgetcsv($handle, 1000, $delimiter)) {
                    if (!$header) {
                        $header = $row;
                    } else {
                       // if(count($header)===count($row)){
                        try{
                            $data[] = array_combine($header, $row);

                        }catch (\Exception $e) {
                            $ok = false;
                            dump($e);
                            $message = '<strong>ERREUR!</strong> <br> Vérifiez que toutes les lignes du fichier se composent de 5 éléments au format 
                                     <br>
                                     <strong> "nom", "prénom", "téléphone", "email", "campus"</strong><br> separées par un virgule où téléphone n\'est pas obligatoire
                                     <br>
                                     <strong> "nom", "prénom", "", "email", "campus" </strong><br>';
                        }
                    }
                    $lineNumber++;
                }
                fclose($handle);
            }
            if($ok){
                $message = 'Format du fichier accepté!';
            }
        } catch (\Exception $e) {
            $ok = false;
            dump($e);
            $message = $e->getMessage();
        }

        return array($data, $ok?'success':'error', $message);
    }
}
