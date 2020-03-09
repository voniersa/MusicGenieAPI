<?php

    class Factory
    {
        public function createApplication()
        {
            return new Application(
                new Router(
                    new Page(
                        new Projector(),
                        new APIConnector()
                    )
                )
            );
        }
    }

?>