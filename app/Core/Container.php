<?php

declare(strict_types=1);

namespace App\Core;

final class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $abstract)
    {
        // Ako već postoji instanca, vrati je
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Ako nema binding-a, pokušaj da kreiraš klasu direktno
        if (!isset($this->bindings[$abstract])) {
            if (class_exists($abstract)) {
                $this->instances[$abstract] = new $abstract();
                return $this->instances[$abstract];
            }
            throw new \Exception("No binding found for {$abstract}");
        }

        $concrete = $this->bindings[$abstract];

        // Ako je closure, izvrši je
        if ($concrete instanceof \Closure) {
            $instance = $concrete($this);
        } else {
            // Ako je string (ime klase), rešavaj rekurzivno
            $instance = $this->resolve($concrete);
        }

        // Sačuvaj instancu za sledeći put
        $this->instances[$abstract] = $instance;

        return $instance;
    }
} 