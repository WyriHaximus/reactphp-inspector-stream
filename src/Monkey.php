<?php declare(strict_types=1);

namespace WyriHaximus\React\Inspector\Stream;

use PhpParser\Node;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use function WyriHaximus\iteratorOrArrayToArray;

final class Monkey
{
    public static function patch(string $class): ReflectionClass
    {
        $classReflection = (new BetterReflection())->classReflector()->reflect($class);
        foreach ($classReflection->getMethods() as $method) {
            self::reroute($method, 'fread');
            self::reroute($method, 'fwrite');
            self::reroute($method, 'stream_get_contents');
        }

        return $classReflection;
    }

    private static function reroute(ReflectionMethod $method, string $functionName): void
    {
        if (strpos($method->getBodyCode(), $functionName) === false) {
            return;
        }

        $method->setBodyFromAst(iteratorOrArrayToArray(self::iterateAst($method->getBodyAst(), $functionName)));
    }

    private static function iterateAst(iterable $ast, string $functionName): iterable
    {
        foreach ($ast as $key => $stmt) {
            yield $key => self::checkStmt($stmt, $functionName);
        }
    }

    private static function checkStmt(Node $stmt, string $functionName): Node
    {
        if (isset($stmt->stmts)) {
            $stmt->stmts = iteratorOrArrayToArray(self::iterateAst($stmt->stmts, $functionName));
        }

        if (isset($stmt->expr)) {
            $stmt->expr = self::checkStmt($stmt->expr, $functionName);
        }

        if (isset($stmt->else) && $stmt->else instanceof Node) {
            $stmt->else = self::checkStmt($stmt->else, $functionName);
        }

        if ($stmt instanceof Node\Expr\FuncCall && $stmt->name->toString() === $functionName) {
            $stmt->name = new Node  \Name('\React\Stream\\' . $stmt->name->toString());
        }

        return $stmt;
    }
}
