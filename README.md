# Doctrine2 - Specification based querying.

[![.github/workflows/main.yaml](https://github.com/frankdejonge/doctrine-query-specification/actions/workflows/main.yaml/badge.svg)](https://github.com/frankdejonge/doctrine-query-specification/actions/workflows/main.yaml)

This packages eases the translation of domain questions to things doctrine can understand.

Query specifications allow you to hook into three stages of the query building process.

1. Applying constraints
2. Modifying the query builder.
3. Modifying the query.

This allows you to encapsulate query logic in bite-sized, small, object. When naming these
objects you can take into account what purpose they fulfill.

## Installation:

```bash
composer require frankdejonge/doctrine-query-specification
```

## Effect

Turn code like this:

```php
// Get the newest 5 articles, needed for front-page
$qb = $articleRepository->createQueryBuilder('a');
$query = $qb
    ->where($qb->expr()->eq('a.published', true))
    ->getQuery()
    ->getResult();
```

Into this:

```php
$articles = $articleRepositoryFieldEquals->findBySpecification(new FrontPageArticles());
```


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
<?php

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class IsPublished implements QueryConstraint
{
    public function asQueryConstraint(QueryBuilder $builder, string $rootAlias): ?object
    {
        $expr = $builder->expr();
        
        return $expr->eq("{$rootAlias}.published", true);
    }
}

$publishedArticles = $articleRepository->findBySpecification(new IsPublished);
```

Query constrains can also accept user-provided input in constructors. When doing so, use
parameterized queries to protect yourself against SQL-injections.

```php
<?php

use Doctrine\ORM\Query\Expr\Composite;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class ArticleHasNameLike implements QueryConstraint
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;

    public function asQueryConstraint(QueryBuilder $builder, string $rootAlias): ?object
    {
        $expr = $builder->expr();
        $builder->setParameter('name_search', $this->name);
        
        return $expr->like("{$rootAlias}.name", ':name_search');
    }
}

$publishedArticles = $articleRepository->findBySpecification(new );
```

### Query modifiers.

```php
<?php

use Doctrine\ORM\Query;
use FrankDeJonge\DoctrineQuerySpecification\QueryModifier;

class AsArray implements QueryModifier
{
    public function modifyQuery(Query $query, string $rootAlias): void
    {
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
    }
}

$publishedArticles = $articleRepository->findBySpecification(new AsArray);
```

### QueryBuilder modifiers.

```php
<?php

use Doctrine\ORM\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryBuilderModifier;

class InReverseOrder implements QueryBuilderModifier
{
    public function modifyQueryBuilder(QueryBuilder $builder, string $rootAlias): void 
    {
        $builder->orderBy("{$rootAlias}.id", "DESC");
    }
}

$publishedArticles = $articleRepository->findBySpecification(new InReverseOrder);
```

## Specification composition

There are three ways of building compositions. Firstly there are specification collections
which allow you to create `andX` and `orX` groups.

```php
$andSpecification = SpecificationCollection::all([
    new IsPublished(),
    new InReversedOrder(),
    new WithAuthor(),
]);

$orSpecification = SpecificationCollection::any([
    new IsFeatured(),
    new IsPublishedToday(),
]);
```

The second way is to create new specification objects which encapsulate one of more other
specifications.

```php
<?php

use Doctrine\DBAL\Query\QueryBuilder;
use FrankDeJonge\DoctrineQuerySpecification\QueryConstraint;

class FeaturedFromAuthor implements QueryConstraint
{
    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    public function asQueryConstraint(QueryBuilder $queryBuilder, $rootAlias)
    {
        $expr = $queryBuilder->expr();
        
        return $expr->andX(
            (new FromAuthor($this->author))->asQueryConstraint($queryBuilder, $rootAlias),
            (new FeaturedArticle)->asQueryConstraint($queryBuilder, $rootAlias),
        );
    }
}
```

Lastly you can extend a generic collection:

```php
<?php

use FrankDeJonge\DoctrineQuerySpecification\SpecificationCollection\All;

class FeaturedFromAuthor extends All
{
    public function __construct(Author $author)
    {
        parent::__construct([
            new FromAuthor($author),
            new FeaturedArticle(),
        ]);
    }
}
```
