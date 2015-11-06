# Doctrine2 - Specification based querying.

This packages eases the translation of domain questions to things doctrine can understand.

## Examples

### Specification aware repositories.

```php
use FrankDeJonge\DoctrineQuerySpecification\SpecificationAwareEntityRepository;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationAwareRepository;
use FrankDeJonge\DoctrineQuerySpecification\SpecificationAwareRepositoryTrait;

class ArticleRepository extends SpecificationAwareEntityRepository
{

}
// OR
class ArticleRepository implements SpecificationAwareRepository
{
    use SpecificationAwareRepositoryTrait;
}
```

### Query constraints.

```php

use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class IsPublished implements QueryContraint
{
    public function asQueryConstraint(QueryBuilder $builder, $rootAlias)
    {
        $expr = $builder->expr();
        
        return $expr->eq("{$rootAlias}.published", true);
    }
}

$publishedArticles = $articleRepository->findBySpecification(new IsPublished);
```

### Query modifiers.

```php

use FrankDeJonge\DoctrineQuerySpecification\QueryModifier;

class AsArray implements QueryModifier
{
    public function modifyQuery(Query $query, $rootAlias)
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }
}

$publishedArticles = $articleRepository->findBySpecification(new AsArray);
```

### QueryBuilder modifiers.

```php

use FrankDeJonge\DoctrineQuerySpecification\QueryBuilderModifier;

class InReverseOrder implements QueryBuilderModifier
{
    public function modifyQueryBuilder(QueryBuilder $builder, $rootAlias)
    {
        $builder->orderBy("{$rootAlias}.id", "DESC");
    }
}

$publishedArticles = $articleRepository->findBySpecification(new InReverseOrder);
```