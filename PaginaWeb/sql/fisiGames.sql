--
-- Estructura de tabla para la tabla `pertenece_a`
--

CREATE TABLE `pertenece_a` (
  `id_cuenta` int(11) NOT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `desbloquean`
--
ALTER TABLE `desbloquean`
  ADD PRIMARY KEY (`id_cuenta`,`id_juego`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `usr_emisor` (`usr_emisor`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_cuenta` (`id_cuenta`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `pertenece_a`
--
ALTER TABLE `pertenece_a`
  ADD PRIMARY KEY (`id_cuenta`,`id_grupo`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `desbloquean`
--
ALTER TABLE `desbloquean`
  ADD CONSTRAINT `desbloquean_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `desbloquean_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id_juego`);

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `cuentas` (`id_cuenta`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`usr_emisor`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`);

--
-- Filtros para la tabla `partidas_jugadas`
--
ALTER TABLE `partidas_jugadas`
  ADD CONSTRAINT `partidas_jugadas_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `partidas_jugadas_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juegos` (`id_juego`);

--
-- Filtros para la tabla `pertenece_a`
--
ALTER TABLE `pertenece_a`
  ADD CONSTRAINT `pertenece_a_ibfk_1` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`),
  ADD CONSTRAINT `pertenece_a_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`);
COMMIT;