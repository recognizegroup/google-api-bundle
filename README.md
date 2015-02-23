Recognize Google Api Bundle
========================

Testing
--------------

To set up the testing enviroment you have to do two things

  * [Install phpunit][1]
  
  * Install the pre-commit hook


[1]:  https://phpunit.de/manual/current/en/installation.html

##Installing the pre-commit hook

Run the following command in the root directory of this project

Linux and Mac:
```
cp .hooks/pre-commit-phpunit .git/hooks/pre-commit
chmod 755 .git/hooks/pre-commit
```

Windows:
```
copy .hooks/pre-commit-phpunit .git/hooks/pre-commit
```

This will make sure the unit tests will be run before each commit.
If you want to disable the unit tests before a commit, you can use the following command

```
git commit --no-verify -m "Commit message!"
```