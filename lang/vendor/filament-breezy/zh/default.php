<?php

return [
    "login" => [
        "username_or_email" => "用户名或电邮",
        "forgot_password_link" => "忘记密码？",
        "create_an_account" => "创建账户",
    ],
    "password_confirm" => [
        "heading" => "确认密码",
        "description" => "请先确认您的密码再进行此操作。",
        "current_password" => "现有密码",
    ],
    "two_factor" => [
        "heading" => "双重认证",
        "description" => "请输入您的验证器应用程序提供的验证码以确认访问您的帐户。",
        "code_placeholder" => "XXX-XXX",
        "recovery" => [
            "heading" => "双重认证",
            "description" => "请输入您的紧急恢复代码以确认对您帐户的访问。",
        ],
        "recovery_code_placeholder" => "abcdef-98765",
        "recovery_code_text" => "丢失设备？",
        "recovery_code_link" => "使用紧急恢复代码",
        "back_to_login_link" => "回到登陆页面",
    ],
    "registration" => [
        "title" => "注册",
        "heading" => "创建新账户",
        "submit" => [
            "label" => "注册",
        ],
        "notification_unique" => "已存在使用此电子邮件的帐户。 请登录。",
    ],
    "reset_password" => [
        "title" => "忘记密码",
        "heading" => "重设密码",
        "submit" => [
            "label" => "提交",
        ],
        "notification_error" => "重置密码错误。请申请重置新密码。",
        "notification_error_link_text"=>"再试一次",
        "notification_success" => "查看您的收件箱以获取说明！",
    ],
    "verification" => [
        "title" => "验证邮箱",
        "heading" => "需要邮箱验证",
        "submit" => [
            "label" => "退出",
        ],
        "notification_success" => "查看您的收件箱以获取说明！",
        "notification_resend" => "验证邮件已重新发送。",
        "before_proceeding" => "在继续之前，请检查您的电子邮件以获取验证链接。",
        "not_receive" => "如果您没有收到邮件，",
        "request_another" => "点击这里请求另一个",
    ],
    "profile" => [
        "account" => "账户",
        "profile" => "个人资料",
        "my_profile" => "我的个人资料",
        "personal_info" => [
            "heading" => "我的个人信息",
            "subheading" => "管理您的个人信息",
            "submit" => [
                "label" => "更新",
            ],
            "notify" => "个人资料成功更新!",
        ],
        "password" => [
            "heading" => "密码",
            "subheading" => "必须是8个字符。",
            "submit" => [
                "label" => "更新",
            ],
            "notify" => "密码成功更新！",
        ],
        "2fa" => [
            "title" => "双重身份验证",
            "description" => "为您的帐户设置双重身份验证（推荐）。",
            "actions" => [
                "enable" => "启动",
                "regenerate_codes"=>"重新生成代码",
                "disable"=>"禁用",
                "confirm_finish" => "确认并完成",
                "cancel_setup" => "取消设置",
            ],
            "setup_key" => "设置密钥",
            "not_enabled" => [
                "title" => "您还没有启用双重身份验证。",
                "description"=>"启用双重身份验证后，系统会在身份验证过程中提示您输入一个安全的随机令牌。您可以从手机的 Google Authenticator 应用程序中检索此令牌。",
            ],
            "finish_enabling" => [
                "title"=>"完成启用双重身份验证。",
                "description" => "要完成启用双重身份验证，请使用手机的身份验证器应用程序扫描以下二维码，或输入设置密钥并提供生成的一次性代码。",
            ],
            "enabled"=>[
                "title"=>"您已启用两因素身份验证！",
                "description"=> "现在启用双重身份验证。使用手机的身份验证器应用程序扫描以下二维码或输入设置密钥。",
                "store_codes"=>"将这些恢复代码存储在安全的密码管理器中。如果您的两因素身份验证设备丢失，它们可用于恢复对您帐户的访问。",
                "show_codes"=> "显示恢复代码",
                "hide_codes" => "隐藏恢复代码",
            ],
            "confirmation" => [
                "success_notification" => '代码已验证。启用两因素身份验证。',
                "invalid_code" => "您输入的代码无效。",
            ],
        ],
        "sanctum" => [
            "title" => "API 令牌",
            "description" => "管理允许第三方服务代表您访问此应用程序的 API 令牌。注意：您的令牌在创建时显示一次。如果您丢失了令牌，您需要将其删除并创建一个新的 .",
            "create" => [
                "notify" => "令牌创建成功！",
                "submit" => [
                    "label" => "创建",
                ],
            ],
            "update" => [
                "notify" => "令牌更新成功！",
            ],
        ],
    ],
    "fields" => [
        "email" => "电子邮件",
        "login" => "登陆",
        "name" => "名称",
        "password" => "密码",
        "password_confirm" => "确认密码",
        "new_password" => "新密码",
        "new_password_confirmation" => "确认新密码",
        "token_name" => "令牌名称",
        "abilities" => "能力",
        "2fa_code" => "代码",
        "2fa_recovery_code" => "恢复代码",
    ],
    "or" => "或",
    "cancel" => "取消",
];
