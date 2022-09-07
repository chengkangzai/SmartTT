<?php

return [
    "login" => [
        "username_or_email" => "Nama pengguna atau E-mel",
        "forgot_password_link" => "Terlupa kata laluan?",
        "create_an_account" => "Cipta akaun",
    ],
    "password_confirm" => [
        "heading" => "Sahkan kata laluan",
        "description" => "Sila sahkan kata laluan anda untuk melengkapkan tindakan ini.",
        "current_password" => "Kata laluan semasa"
    ],
    "two_factor" => [
        "heading" => "Cabaran Dua Faktor",
        "description" => "Sila sahkan akses kepada akaun anda dengan memasukkan kod pengesahan yang disediakan oleh aplikasi pengesah anda.",
        "code_placeholder" => "XXX-XXX",
        "recovery" => [
            "heading" => "Cabaran Dua Faktor",
            "description" => "Sila sahkan akses kepada akaun anda dengan memasukkan salah satu kod pemulihan kecemasan anda.",
        ],
        "recovery_code_placeholder" => "abcdef-98765",
        "recovery_code_text" => "Peranti hilang?",
        "recovery_code_link" => "Gunakan kod pemulihan",
        "back_to_login_link" => "Kembali ke log masuk"
    ],
    "registration" => [
        "title" => "Daftar",
        "heading" => "Buat akaun baharu",
        "submit" => [
            "label" => "Daftar",
        ],
        "notification_unique" => "Akaun telah wujud. Sila log masuk.",
    ],
    "reset_password" => [
        "title" => "Terlupa kata laluan",
        "heading" => "Tetapkan semula kata laluan anda",
        "submit" => [
            "label" => "Serah",
        ],
        "notification_error" => "Ralat semasa menetapkan semula kata laluan. Sila minta penetapan semula kata laluan baharu.",
        "notification_error_link_text" =>"Cuba Lagi",
        "notification_success" => "Semak peti masuk anda untuk mendapatkan arahan!",
    ],
    "verification" => [
        "title" => "Sahkan e-mel",
        "heading" => "Pengesahan e-mel diperlukan",
        "submit" => [
            "label" => "Log keluar",
        ],
        "notification_success" => "Semak peti masuk anda untuk mendapatkan arahan!",
        "notification_resend" => "E-mel pengesahan telah dihantar semula.",
        "before_proceeding" => "Sebelum meneruskan, sila semak e-mel anda untuk mendapatkan pautan pengesahan.",
        "not_receive" => "Jika anda tidak menerima e-mel,",
        "request_another" => "klik di sini untuk meminta yang lain",
    ],
    "profile" => [
        "account" => "Akaun",
        "profile" => "Profil",
        "my_profile" => "Profil saya",
        "personal_info" => [
            "heading" => "Maklumat Peribadi",
            "subheading" => "Kemaskini maklumat peribadi anda",
            "submit" => [
                "label" => "Kemaskini",
            ],
            "notify" => "Maklumat peribadi anda telah dikemaskini.",
        ],
        "password" => [
            "heading" => "Kata Laluan",
            "subheading" => "Mestilah 8 aksara.",
            "submit" => [
                "label" => "Kemaskini",
            ],
            "notify" => "Kata laluan anda telah dikemaskini.",
        ],
        "2fa" => [
            "title" => "Pengesahan Dua Faktor",
            "description" => "Uruskan pengesahan 2 faktor untuk akaun anda (disyorkan)",
            "actions" => [
                "enable" => "Dayakan",
                "regenerate_codes" =>"Regenerate Codes",
                "disable" =>"Lumpuhkan",
                "confirm_finish" => "Sahkan & selesaikan",
                "cancel_setup" => "Batalkan persediaan"
            ],
            "setup_key" => "Kunci persediaan",
            "not_enabled" => [
                "title" => "Anda belum mendayakan pengesahan dua faktor.",
                "description" =>"Apabila pengesahan dua faktor didayakan, anda akan digesa untuk mendapatkan token rawak yang selamat semasa pengesahan. Anda boleh mendapatkan semula token ini daripada aplikasi Google Authenticator telefon anda."
            ],
            "finish_enabling" => [
                "title" =>"Selesai mendayakan pengesahan dua faktor.",
                "description" => "Untuk menyelesaikan mendayakan pengesahan dua faktor, imbas kod QR berikut menggunakan aplikasi pengesah telefon anda atau masukkan kekunci persediaan dan berikan kod OTP yang dijana."
            ],
            "enabled"=>[
                "title" =>"Anda telah mendayakan pengesahan dua faktor!",
                "description" =>"Pengesahan dua faktor kini didayakan. Imbas kod QR berikut menggunakan aplikasi pengesah telefon anda atau masukkan kekunci persediaan.",
                "store_codes" =>"Simpan kod pemulihan ini dalam pengurus kata laluan yang selamat. Ia boleh digunakan untuk memulihkan akses kepada akaun anda jika peranti pengesahan dua faktor anda hilang.",
                "show_codes" => 'Tunjukkan Kod Pemulihan',
                "hide_codes" => 'Sembunyikan Kod Pemulihan'
            ],
            "confirmation" => [
                "success_notification" => 'Kod disahkan. Pengesahan dua faktor didayakan.',
                "invalid_code" => "Kod yang anda masukkan tidak sah."
            ]
        ],
        "sanctum" => [
            "title" => "Token API",
            "description" => "Urus token API yang membenarkan perkhidmatan pihak ketiga mengakses aplikasi ini bagi pihak anda. NOTA: token anda ditunjukkan sekali selepas dibuat. Jika anda kehilangan token anda, anda perlu memadamkannya dan mencipta yang baharu .",
            "create" => [
                "notify" => "Token API baru telah dicipta.",
                "submit" => [
                    "label" => "Cipta Token Baru"
                ],
            ],
            "update" => [
                "notify" => "Token API telah dikemaskini.",
            ],
        ],
    ],
    "fields" => [
        "email" => "E-mel",
        "login" => "Log masuk",
        "name" => "Nama",
        "password" => "Kata laluan",
        "password_confirm" => "Sahkan kata laluan",
        "new_password" => "Kata laluan baharu",
        "new_password_confirmation" => "Sahkan kata laluan baharu",
        "token_name" => "Nama Token",
        "abilities" => "Kemampuan",
        "2fa_code" => "Kod Pengesahan Dua Faktor",
        "2fa_recovery_code" => "Kod Pemulihan Pengesahan Dua Faktor",
    ],
    "or" => "atau",
    "cancel" => "Batal",
];
