<?php


namespace App\Services;


use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class ValidateUserCSV
{
public static function validate($readerData, $campus):string{

    $validationMessage='';
    $validationErrors=[];
    $lineNb = 0;
    $validator = Validation::createValidator();
    foreach($readerData as $userCSV) {
        $input = [
            'nom' => $userCSV['nom'],
            'prenom' => $userCSV['prenom'],
            'telephone' => $userCSV['telephone'],
            'mail' => $userCSV['mail'],
            'campus' => trim(ucwords(strtolower($userCSV['campus'])))
        ];
        $constraints = new Collection([
            'nom' => [new Length(['max' => 50]), new NotBlank()],
            'prenom' => [new Length(['max' => 50]), new NotBlank()],
            'telephone' => [new Length(['max' => 10])],
            'mail' => [new Length(['max' => 180]), new NotBlank(), new Email()],
            'campus' => [new Choice($campus)],
        ]);
        $violations = $validator->validate($input, $constraints);
        $lineNb++;
        if(count($violations)>0){
            foreach($violations as $violation){
                $validationErrors[]=[$lineNb, $violation];
            }
        }
    }
    if (0 !== count($validationErrors)) {
       // dump($validationErrors);
        $validationMessage.= '<strong>ERREUR!</strong> <br>';
        foreach($validationErrors as $error){
            $validationMessage.= 'Ligne '.$error[0].' propriété '.$error[1]->getPropertyPath().' - '.$error[1]->getMessage(). ' <br>';
        }
    }
    if($validationMessage != ''){
        $validationMessage.= ' <hr><strong>Conditions:</strong><br>
                            nom : 50 caractères max / obligatoire <br>
                            prènom : 50 caractères max / obligatoire <br>
                            téléphone : 10 caractères max  <br>
                            email : adresse mail valide / obligatoire <br>
                            campus : campus parmi '.implode(", ",$campus).' / obligatoire 
                            ';
    }
   // dump($validationMessage);
    return $validationMessage;
}
}