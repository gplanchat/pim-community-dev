<?php

namespace Oro\Bundle\FilterBundle\Datasource\Orm;

use Doctrine\ORM\Query\Expr;
use Oro\Bundle\FilterBundle\Datasource\ExpressionBuilderInterface;

class OrmExpressionBuilder implements ExpressionBuilderInterface
{
    protected $expr;

    public function __construct(Expr $expr)
    {
        $this->expr = $expr;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function andX($_)
    {
        return call_user_func_array([$this->expr, 'andX'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function orX(...$_)
    {
        return call_user_func_array([$this->expr, 'orX'], func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function comparison($x, $operator, $y, $withParam = false)
    {
        return new Expr\Comparison($x, $operator, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function eq($x, $y, $withParam = false)
    {
        return $this->expr->eq($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function neq($x, $y, $withParam = false)
    {
        return $this->expr->neq($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function lt($x, $y, $withParam = false)
    {
        return $this->expr->lt($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function lte($x, $y, $withParam = false)
    {
        return $this->expr->lte($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function gt($x, $y, $withParam = false)
    {
        return $this->expr->gt($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function gte($x, $y, $withParam = false)
    {
        return $this->expr->gte($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function not($restriction)
    {
        return $this->expr->not($restriction);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function in($x, $y, $withParam = false)
    {
        return $this->expr->in($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function notIn($x, $y, $withParam = false)
    {
        return $this->expr->notIn($x, $withParam ? ':' . $y : $y);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isNull($x)
    {
        return $this->expr->isNull($x);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isNotNull($x)
    {
        return $this->expr->isNotNull($x);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function like($x, $y, $withParam = false)
    {
        return $this->expr->like($x, $withParam ? ':' . $y : $y);
    }
}
