<?php

/**
 * Parses a CSV file and returns an array of rows.
 *
 * @param string $filepath The path to the CSV file.
 * @return array The parsed data.
 * 
 * Example output:
 * [
 *     [
 *         'First Name' => 'John',
 *         'Gender' => 'Male',
 *         'Start Date' => '2020-01-15',
 *         'Salary' => '50000',
 *         'Department' => 'Engineering'
 *     ],
 *    ...
 * ]
 */
function parse_csv(string $filepath): array
{
    $handle = fopen($filepath, 'r');
    if (!$handle) {
        return [];
    }

    // get the lines of the file and remove any trailing newlines, so we can process the data correctly
    $lines = [];
    while (($line = fgets($handle)) !== false) {
        $lines[] = rtrim($line, "\r\n");
    }
    fclose($handle);

    if (empty($lines)) {
        return [];
    }

    // we getting the headers by the first row, the method is done with explode where itøs splitting the string by the comma, so we can get the headers as an array
    $headers = explode(',', $lines[0]);
    $rows = [];
    $totalLines = count($lines);
    $totalHeaders = count($headers);

    for ($i = 1; $i < $totalLines; $i++) {
        if ($lines[$i] === '') {
            continue;
        }

        $values = explode(',', $lines[$i]);
        // array pad is using for fullfilling the missing values in the row, so we always have the correct number of columns
        $values = array_pad($values, $totalHeaders, '');

        $row = [];
        for ($j = 0; $j < $totalHeaders; $j++) {
            $row[$headers[$j]] = $values[$j];
        }

        $rows[] = $row;
    }

    return $rows;
}

/**
 * Filters an array of employees based on a search term.
 *
 * @param array $employees The array of employees to filter.
 * @param string $search The search term to filter by.
 * @return array The filtered array of employees.
 */
function filter_employees(array $employees, string $search): array
{
    if ($search === '') {
        return $employees;
    }

    $result = [];
    foreach ($employees as $employee) {
        // stripos will find the search term in the fields of the employee array
        // implode will just combine the fields to a single string, so its just a single search
        if (stripos(implode(' ', $employee), $search) !== false) {
            $result[] = $employee;
        }
    }
    return $result;
}

/**
 * Groups an array of employees by their department.
 *
 * @param array $employees The array of employees to group.
 * @return array The grouped array of employees.
 */
function group_by_department(array $employees): array
{
    $groups = [];
    $noDepartment = 'No Department';
    foreach ($employees as $employee) {
        $dept = $employee['Department'] === '' ? $noDepartment : $employee['Department'];
        $groups[$dept][] = $employee;
    }
    // since ksort is just a simple alphabetical sort, so we using uksort to sort with a custom function to make sure that "No Department" is always at the bottom of the list
    uksort($groups, function ($a, $b) use ($noDepartment) {
        if ($a === $noDepartment) return 1; 
        if ($b === $noDepartment) return -1;
        return $a <=> $b;
    });

    return $groups;
}

/**
 * Escapes a string for safe output in HTML.
 *
 * @param string $value The string to escape.
 * @return string The escaped string.
 */
function escape(string $value): string
{
    // used for escaping the output to prevent XSS attacks, by converting special characters to HTML entities
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
