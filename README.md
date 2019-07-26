# edu-auth
shanghai edu unified identity authentication

## install
`composer require mo-lin/edu-auth`

## Usage

1. Set a redirect url
```
   //for example
   // http://x.xx.xxx/callback
```

2. Get auth object 
```
   $auth = new \EduOauth\Auth();
```

3. Set redirect url to auth object 
```
   $auth = $auth->getOauthLink('http://x.xx.xxx/callback');
   // redirect or other things ......
```


4. You can receive a code in callback url, then do as below
```
    // code
    $code = $GET['code'];
    $auth = (new \EduOauth\Auth())->setCallBack('http://x.xx.xxx/callback');
    $userInfo = $auth->getUserInfo();
```
