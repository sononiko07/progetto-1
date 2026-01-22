<?php
    echo"
        <DOCTYPE html>
        <html>

            <head>
                <title>Login</title>
                <link rel='stylesheet' href='style.css'>
                <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Roboto:wght@400;700&display=swap' rel='stylesheet'>
            </head>

            <body>
                <div class='glass-card' style='width: 500px; max-width: 95%;'>
                    <h2 class='centered-text form-title'>🎄BENVENUTI🎄</br></h2>
                    <h4 class='centered-text form-title'>EFFETTUARE IL LOGIN</br></h2>

                    <form action='/area/area.php' method='POST' style='display:flex; flex-direction: column; gap: 10px;'>
                    
                        <div class='input-group form-title' >
                            <label style='color: rgba(0, 0, 0, 0.7); font-weight: bold; font-size: 1em; margin-left: 5px;'>Email</label>
                            <input type='text' name='user' class='form-control' placeholder='Inserisci email' required>
                        </div>
                       
                        <div class='input-group form-title'>
                            <label style='color: rgba(0, 0, 0, 0.7); font-weight: bold; font-size: 1em; margin-left: 5px;'>Password</label>
                            <input type='password' name='pass' class='form-control' placeholder='Inserisci password' required>
                        </div>
                        
                        <button type='submit' class='btn-custom' style='padding: 12px; margin-top: 20px; cursor: pointer;'>
                            ACCEDI
                        </button>
                      
                        <div class='centered-text' style='margin-top: 10px;'>
                            <a href='nome_pagina_reset_password' style='color: white; font-size: 0.8em; text-decoration: none; opacity: 0.8;'>Password dimenticata?</a>
                        </div>
                    </form>
                </div>
            </body>
        </html>

    ";
?>