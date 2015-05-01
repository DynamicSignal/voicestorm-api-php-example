<?php

require_once "functions.php";

function modifyUser($user)
{
    $userid = $user['id'];
    $voicestormAffiliations = voicestormApiRequest("GET", "/manage/affiliations");
    if (count($voicestormAffiliations['affiliations']) == 0)
    {
        echo 'No Affiliations in the Sphere'; // No affiliations found in the community
    }
    else
    {
        // remove all affiliations from the user (the set call overwrites existing affiliations, so this is just an example)
        voicestormApiRequest("PUT", "/manage/user/" . $userid . "/affiliations", array("affiliations" => array()));

        // set some affiliations for the user
        $userAffiliations = setAffiliations($voicestormAffiliations['affiliations'], $userid);
        echo var_dump($userAffiliations);
    }

    $voicestormDivisions = voicestormApiRequest("GET", "/divisions");
    if (isset($voicestormDivisions['code']) && $voicestormDivisions['code'] == "not_enabled")
    {
        echo 'Divisions Not Enabled for the current community';
    }
    else
    {
        if (count($voicestormDivisions['divisions']) == 0)
        {
            echo "Zero divisions set"; //No divisions found in the community
        }
        else
        {
            // remove all divisions from the user (the set call overwrites existing divisions, so this is just an example)
            voicestormApiRequest("PUT", "/user/" . $userid, array('divisions' => array()));

            // set some divisions for the user
            $userDivisions = setDivisions($voicestormDivisions['divisions'], $userid);
            echo var_dump($userDivisions);
        }
    }

    // get a fresh copy of the newly updated user
    $resultUser = voicestormApiRequest("GET", "/user/" . $userid, array("include" => "affiliations"));
    return $resultUser;
}

function setAffiliations($affiliations, $userid)
{
    $constructUserAffiliationsCollection = array();
    foreach ($affiliations as $affiliation)
    {
        switch ($affiliation['question']['questionType'])
        {
            case "SingleAnswer":
                array_push($constructUserAffiliationsCollection, array("question" => $affiliation['question'], "answer" => current($affiliation['answers']))); //Get the first answer option
                break;
            case "MultipleAnswer":
                array_push($constructUserAffiliationsCollection, array("question" => $affiliation['question'], "answer" => current($affiliation['answers']))); // Get the first answer value
                if (next($affiliation['answers'])) //check if there is second option and move the pointer
                {
                    array_push($constructUserAffiliationsCollection, array("question" => $affiliation['question'], "answer" => current($affiliation['answers']))); // Get the second answer value 
                }
                break;
            case "FreeText":
                $freeTextAnswer = 'Here\'s a sample answer to the free text queston!'; //Set the Free Text content
                $getAffiliationAnswer = current($affiliation['answers']);
                $getAffiliationAnswer['freeText'] = $freeTextAnswer;
                array_push($constructUserAffiliationsCollection, array("question" => $affiliation['question'], "answer" => $getAffiliationAnswer));
                break;
        }
    }
    $userResponse = voicestormApiRequest("PUT", "/manage/user/" . $userid . "/affiliations", array("affiliations" => $constructUserAffiliationsCollection));
    return $userResponse['affiliations'];
}

function setDivisions($divisions, $userid)
{
    $userResponse = voicestormApiRequest("PUT", "/user/" . $userid, array('divisions' => current($divisions))); // Set to first division
    return $userResponse['divisions'];
}

if (isset($_POST['email']))
{
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $result = voicestormApiRequest("PUT", "/search", array("term" => $email, "type" => "User", "take" => "2")); //You can get as many users as you want
        if (isset($result["code"]) && $result["code"] == "error")
        {
            $retVal = array("result" => "error", "message" => $result["message"]);
            die(var_dump($retVal));
        }
        else if (isset($result["results"]))
        {
            if (count($result['results']) == 0) // No user found with given term
            {
                $userResponse = voicestormApiRequest("PUT", "/register", array("email" => $email, "firstName" => 'test', "lastName" => "testlast", "password" => "password")); //Make sure that the email is in right format
                if (isset($userResponse['user']))
                {
                    $modifiedUser = modifyUser($userResponse['user']);
                    echo var_dump($modifiedUser);
                }
            }
            else // User found with given term
            {
                if (count($result['results']) == 1) // Single user with given term
                {
                    $currentUser = current($result['results']);
                    $modifiedUser = modifyUser($currentUser['user']);
                    echo var_dump($modifiedUser);
                }
                else
                {
                    foreach ($result['results'] as $currentResult)
                    {
                        $modifiedUser = modifyUser($currentResult['user']);
                        echo var_dump($modifiedUser);
                    }
                }
            }
        }
        else
        {
            $retVal = $result;
            echo var_dump($retVal); //Not sure when it gets hit
        }
    }
    else
    {
        echo "$email is <strong>NOT</strong> a valid email address.<br/><br/>";
    }
}
else
{
    header('Location: index.php'); die(); //No email address received
}
?>