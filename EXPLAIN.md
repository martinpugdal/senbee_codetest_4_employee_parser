# Explanation

## 1. CSV Parsing (`parse_csv`)

The function reads the CSV file using `fgets` where its giving me raw lines without any CSV logic. The parser is created based on the logic of the first line is always a column headers. Each line is splitted on commas with `explode(',', ...)`. Then the `array_pad` will fullfill the empty strings in the empty strings, so each row has the same structure. The last thing is the loop of the values, and we are mapping them to their header keys, so we can return an array of associsative arrays.

## 2. Search Filter (`filter_employees`)

When a search term is submitted via the form, the function will loop through every employee and use the method `stripos` to check for the term is appearing in any fields of the employee, and the method is also case-insentitive. We use the `implode(' ', $employee)` to combine all field values to a single string, so the search will pass once. If none search term is provided, the responds is all employees.

## 3. Grouping Logic (`group_by_department`)

Employees have a `Department` field and they will be grouped by that field. We are using a temporary associative array, where the key is the department name. We also created a key called `No Department`, and its for all employees without a department. Since we decided to make the output more accure, we used the `uksort` to sort the departments alphabeticaally, where the `No Department` will always be the last key in the list, since the `uksort` requires a callback, so we made a custom comparion.
