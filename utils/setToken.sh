#check if root
if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
  exit
fi

if [ $# -eq 0 ]
  then
    echo "No arguments supplied"
    echo "Run this script like sudo bash setToken.sh TOKEN"
    echo "Where TOKEN is your GitHub personal access token"
    echo "An access token is needed to perform automatic operations and link them to an account."
    echo ""
    echo "If you don't know how to create an access token follow these simple steps:"
    echo "1- Login or create your GitHub account."
    echo "2- In the top right corner of any page of GitHub.com, click your profile photo, then click Settings."
    echo "3- In the user settings sidebar, click Personal access tokens."
    echo "4- Click Generate new token."
    echo "5- Give your token a descriptive name."
    echo "6- Select the scopes you wish to grant to this token."
    echo "The default scopes allow you to interact with public and private repositories, user data, and gists."
    echo "7- Click Generate token."
    echo "Run this script like sudo bash setToken.sh TOKEN"
    exit 1
else
    echo "Saving the access token"
    sudo sed -i "s/YOURGITHUBPERSONALACCESSTOKENHERE/$1/g" /var/www/drink-maker/composer.json
fi
