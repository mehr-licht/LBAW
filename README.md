# Group lbaw2036 - README


## 1. Installation

The source code is available at
[https://git.fe.up.pt/lbaw/lbaw1920/lbaw2036/tags/a9](https://git.fe.up.pt/lbaw/lbaw1920/lbaw2036/tags/a9)
|comandos Docker para correr a imagem:|
| --- |
| ```docker run -it -p 8000:80 -e DB_DATABASE="lbaw2036" -e DB_USERNAME="lbaw2036" -e DB_PASSWORD="ZF311532" lbaw2036/lbaw2036``` |

To build the image:
1. ```composer install``` ou ```composer dump-autoload```
2. ```php artisan config:clear```
3. ```php artisan route:clear```
4. ```php artisan cache:clear```
5. ```php artisan clear-compiled```
6. ```docker build -t lbaw2036/lbaw2036 .```
 

## 2. Usage

product URL: [http://lbaw2036.lbaw-prod.fe.up.pt/](http://lbaw2036.lbaw-prod.fe.up.pt/)

### 2.1. Administration Credentials

Administration URL:  [http://lbaw2036.lbaw-prod.fe.up.pt/admin/login](http://lbaw2036.lbaw-prod.fe.up.pt/admin/login)


| Username  | Password |
| --------- | -------- |
| admin@ebaw.pt | lbaw1234 |

### 2.2. User Credentials

| Type          | Username     | Password |
| ------------- | ------------ | -------- |
| basic account | john@example.com | lbaw1234 |







NOTES:
    https://web.fe.up.pt/~jlopes/doku.php/teach/lbaw/plan

Checklist: 
    https://docs.google.com/spreadsheets/d/1yY8oGB3boc3VJSCgxidznMwqidh7gXIywcidCzbD5rY/edit?ts=5e417e5d#gid=537406521

SpeelChecker:
    https://www.scribens.com/

PDF_Coverter:
    http://markdown2pdf.com/
    
```
    php artisan make:migration create_users_table
    php artisan make:migration create_users_table
    php artisan db:seed --class=UsersTableSeeder

    chmod -R gu+w storage
    chmod -R guo+w storage
    php artisan cache:clear
```


## Tema 6 - Online auctions

Com este projeto pretendemos criar uma plataforma online para leilões, oferecendo tanto leilões como venda direta de uma grande variedade de produtos.


## Development Support

[Git Cheat Sheet](https://git.fe.up.pt/lbaw/lbaw20/lbaw2036/blob/master/Git_Cheat_Sheet.md)

[How to run & Dev](https://git.fe.up.pt/lbaw/lbaw1920/lbaw2036/blob/master/How_to_run.md)

[Docker how-to](https://git.fe.up.pt/lbaw/lbaw1920/lbaw2036/blob/master/docker_how_to.md)

[Laravel Cheat Sheet](https://learninglaravel.net/cheatsheet/)

## Checklist 2036

[LBAW 1920: 2036 Checklists](https://docs.google.com/spreadsheets/d/1yY8oGB3boc3VJSCgxidznMwqidh7gXIywcidCzbD5rY/edit#gid=537406521)

## Membros do grupo:

* Luis Ricardo Marques Oliveira, up201607946@fe.up.pt 
* Henrique Miguel Bastos Gonçalves, up201608320@fe.up.pt
* João Ruano Neto Veiga de Macedo, up201704464@fe.up.pt
* Ricardo Manuel Gonçalves da Silva, up201607780@fe.up.pt (editor)


## Historico de editores:
A1-Ricardo\
A2-João\
A3-Luis\
resubs-Henrique\
A4-Ricardo\
A5-João\
A6-Luis\
resubs-Henrique\
A7-Luis\
A8-Luis\
resubs-João\
A9-Ricardo\
A10-


***
GROUP2036, 18/02/2020
# Group lbaw2036 - README

JCL: Atendimento online: quintas, 16:00 ~ 17:00

MEET da prática: [https://meet.google.com/zxv-dvmd-nzv](https://meet.google.com/zxv-dvmd-nzv)


NOTES:
    https://web.fe.up.pt/~jlopes/doku.php/teach/lbaw/plan

Checklist: 
    https://docs.google.com/spreadsheets/d/1yY8oGB3boc3VJSCgxidznMwqidh7gXIywcidCzbD5rY/edit?ts=5e417e5d#gid=537406521

SpellChecker:
    https://www.scribens.com/

PDF_Coverter:
    http://markdown2pdf.com/



# login data

### servidor postgresql
* postgres
* postgres
* pg!lol!2020

### login postgres @ dbm
* lbaw2036
* ZF311532

### docker
* lbaw2036/lbaw2036
* lbaw2036ebaw


# How to run



The following list assumes the required software is installed and updated:

Run on terminal:
 ``` docker-compose up  ```

In another terminal:

```
    php artisan migrate:fresh
    php artisan db:seed --class=DatabaseSeeder
    php artisan serve
 ```

Other necessary commands in case of error:
```   
  php artisan optimize:clear
    php artisan config:clear
    php artisan config:cache
    composer dumb-autoload
```
    
Necessary when changes are made in database
     ```     
php artisan migrate:fresh 
 ```

Line command to test queries and check inserts, updates and deletes
     ```  
   php artisan tinker 
 ```

Seeds dummy data in database to test and developement 
    ```     
php artisan db:seed --class=DatabaseSeeder 
 ```

Creates a model to define and interact with table Product
    ```
 php artisan make:model Product  
```

Creates a controller with CRUD methods
```  
php artisan make:controller ProductController --resource 
```

Lists all implemented routes 
```
php artisan route:list
```

more
```
php artisan migrate:status
php artisan migrate:help
```

# container    

### Build image:
``` docker build -t lbaw2036/lbaw2036 .  ```

### test image in docker after build:
 ``` docker run -it -p 8000:80 lbaw2036/lbaw2036   ```

### upload image:
 ``` ./upload_image.sh  ```



Git is the open source distributed version control system that facilitates GitLab activities on your laptop or desktop. This cheat sheet summarizes commonly used Git command line instructions for quick reference

Template:

    <command>
    <comments>

## Configure Tooling

### Configure user information for all local repositories
```
    git config --global user.name "[name]" ```
Sets the name you want attached to your commit transactions

  ```   git config --global user.email "[email address]"  ```
Sets the email you want attacehd to your commit transactions

   ```  git config --global color.ui auto ```
Enables helpful colorization of command line output

## Create Repository

### Start a new repository or obtain one from an existing URL

   ```  git init [proj-name] ```
Creates a new local repository with the specified name

   ```  git clone [url] ```
Downloads a project and its entire version history

## Make changes

### Review edits and craft a commit transaction

  ```   git status ```
Lists all new or modified files to be committed

  ```   git diff ```
Shows file differences not yet staged

  ```   git add [file] ```
Snapshots the file in preparation for versioning

 ```    git diff --staged ```
Shows file differences between staging and the last file version

 ```    git reset [file] ```
Unstages the file, but preserves its contents

 ```    git commit -m "[descriptive message]" ```
Records file snapshots permanently in version history

## Group changes

### Name a series of commits and combine compelted efforts

  ```   git branch ```
Lists all local branches in the current repository

 ```    git branch [branch-name]
Creates a new branch ```

  ```   git checkout [branch-name] ```
Switches to the specified branch and updates the working directory

   ```  git merge [branch] ```
Combines the specified branch's history into the current branch
    
  ```   git branch -d [branch-name] ```
Deletes the specified branch

## Refactor filenames

### Relocate and remove versioned files


  ```   git rm [file] ```
Deletes the fle from the working repository and stages the deletion

 ```    git rm --cached [file] ```
Removes the file from version control but preserves the file locally

   ```  git mv [file-original] [file-renamed] ```
Changes the file name and prepares it for commit

## Supress tracking

### Shelve and restore incomplete changes


 ```    git ls-files --other --ignored --exclude-standard ```
Lists all ignored files in this project.    
A text file named .gitignored suppresses accidental versioning of file and paths matching the specified patterns

## Shave fragments

### Shelve and restore incomplete changes

   ```  git stash ```
Temporarily stores all modified tracked files
    
   ```  git stash pop ```
Restores the most recently stashed files

   ```  git stash list ```
Lists all stashed changesets

   ```  git stash drop ```
Discards the most recently stashed changeset


## Review history

### Browse and inspect the evolution of project files


    ``` git log ``` 
Lists version history for the current branch

   ```  git log --follow [file] ```
Lists version history for a file, including renames

  ```   gid diff [first-branch] [second-branch]
Shows content differences between two branches

  ```   git show [commit] ```
Outputs metadata and content changes of the specified commit

## Redo Commits

### Erase mistakes and craft replacement history

 ```    git reset [commit] ```
Undoes all commits after [commit], preserving changes locally

  ```   git reset --hard [commit] ```
Discards all history and changes back to the specified commit

## Syncronize Changes

### Register a repository bookmark and exchange version history

  ```   git fetch [bookmark] ```
Downloads all history from the repository bookmark

  ```   git merge [bookmark]/[branch] ```
Combines bookmark's branch into current local branch

     ```git push [alias] [branch] ```
Uploads all local branch commits to Github

    ``` git pull ```
Downloads bookmark history and incorporates changes 

