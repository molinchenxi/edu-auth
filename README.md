# edu-auth
shanghai edu unified identity authentication

## install
`composer require mo-lin/edu-auth`

## Usage

1. 获取三方调用链接
```
   $auth = new \EduOauth\Auth();
   $link = $auth->getOauthLink('http://x.xx.xxx/callback');
```


2. 通过 code 获取三方用户信息
```
    $code = $GET['code'];
    $auth = (new \EduOauth\Auth())->setCallBack('http://x.xx.xxx/callback');
    $userInfo = $auth->getUserInfo();
    
```
