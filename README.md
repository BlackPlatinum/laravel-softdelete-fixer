# laravel-softDelete-fixer
### Description: 
As you know when you join a table using `Eloquent` join methods, it doesn't check soft delete and join all rows even it's `deleted_at` column is not `null`.   
With this package and using it in your models, it will check soft deleted rows.   
### Usage:
#### First:
Add this line to your models    
`Use SoftDeletesFix;`
#### Second:
Add this key to your `.env` file with namespace of you model classes    
`MODEL_NAMESPACE=`

