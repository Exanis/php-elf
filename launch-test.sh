#!/bin/bash

TESTING_FRAMEWORK=phpunit
SRC_PATH=src
TEST_LIST=`find "$SRC_PATH/testing" -name "*.php" -print`

echo "############## STARTING TEST #################"
echo "# FRAMEWORK : $TESTING_FRAMEWORK"
echo "# SOURCE PATH : $SRC_PATH"
echo "# TESTS LIST :"
for testName in $TEST_LIST
do
    echo "#	$testName"
done;
echo "##############################################"

phpunit  --coverage-text --report-useless-test --strict-coverage --colors --bootstrap "$SRC_PATH/autoload.php" $TEST_LIST