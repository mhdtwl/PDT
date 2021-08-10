# Patient Data Transform [ PDT ]. 

This application runs a cli command to transform dataset from one to another shape. 
## How to run
I introduce a config for data mapping using json file / map.json.

> git clone git@github.com:mhdtwl/PDT.git
>   
> cd PDT
> 
> composer install
> 
> php artisan export:transform-patients 

Behind the scenes it has the default to read/write csv files from  storage/app/data

Find it at line TransformPatientsCommand::100. 
 
> php artisan export:transform-patients \
>  --i ./storage/data/input.csv  \
>  --o ./storage/data/output.csv  \
>  --m ./storage/data/map.json

## Architecture & SOLID/DRY

  The request goes on the following flow:
1. Command -> exportFactory -> ExportReader  and returns dataset.
2. ConfigMapperService converts config json file for source/target input/output and the 
   data format.
   1. Uses validation & ConfigConverter [ Json <-> DTO ]
3. PatientService gets config + dataset and then 
   1. Uses PatientConverter to get the data transformed.
   2. transforms the patient data based on field type [ numeric / date ...etc ]
   3. transformation on numeric type supported for [ + - * / ] operations.
   4. set the order of the fields.
   5. returns header + transformed data. 
4. ExportWriter gets the header + dataset and creates an export.
5. Unit test and Integration test are available on data sample 
   
   > vendor/bin/phpunit tests/Unit 
   > 
   >  vendor/bin/phpunit tests/Integration    
   
### SOLID / DRY 
- Single responsibility:  just like Libraries::CsvReader\CsvWriter,  
- Easy to extend where a new field type to add to FieldTypeEnum
- With minimal efficient code implementation.

### Technology stack

- Laravel Lumen: 8.0 / PHP micro-framework to run commands
- league/csv: 9.0 / For Read/Write CSVs
- spatie/data-transfer-object: 2.8 / For DTOs

### Things to improve

- To support more options --stdout to print on cli...etc.
- Set chucks limit on read/write to consider memory limitation.

### License

The Lumen framework is open-sourced software licensed under
the [MIT license](https://opensource.org/licenses/MIT).
