import React from 'react'
import {useState} from 'react'

let inputEmail = [updateEmail,setUpdateEmail] = useState(false);
let inputPassword = [updatePassword, setUpdatePassword] = useState(false); 

let modal = [showModal, setShowModal] = useState(false)


export default function () {
 function showModalOn () {
    setShowModal(true);
    console.log("Modal show", modal)
 }

    return (
        <div>
            <button type="submit" onClick={showModalOn}>Show Modal</button>

            {
            modal && (
                <div>
                    modal
                </div>

                )
            }
        </div>



    )
}