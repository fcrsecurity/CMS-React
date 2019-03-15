#!/bin/bash

# Set Default Variables
projectsPath="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

php-cs-fixer fix $projectsPath/src/ --rules=@PSR2